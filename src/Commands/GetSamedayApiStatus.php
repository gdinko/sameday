<?php

namespace Mchervenkov\Sameday\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Mchervenkov\Sameday\Models\SamedayApiStatus;
use Mchervenkov\Sameday\Sameday;

class GetSamedayApiStatus extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sameday:api-status
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Sameday API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Sameday API Status and saves it in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('-> Sameday Api Status');

        try {
            $this->clear();

            $sameday = $this->initSamedayClient();

            $lockers = $sameday->getLockers();

            if (! empty($lockers)) {
                SamedayApiStatus::create([
                    'code' => self::API_STATUS_OK,
                ]);

                $this->info('Status: ' . self::API_STATUS_OK);
            }
        } catch (\Exception $e) {
            SamedayApiStatus::create([
                'code' => self::API_STATUS_NOT_OK,
            ]);

            $this->newLine();
            $this->error('Status: ' . self::API_STATUS_NOT_OK);
            $this->error(
                $e->getMessage()
            );

            return 1;
        }

        return 0;
    }

    /**
     * @return Sameday
     */
    private function initSamedayClient(): Sameday
    {
        $sameday = new Sameday();

        if($timeout = $this->option('timeout')) {
            $sameday->setTimeout((int)$timeout);
        }

        return $sameday;
    }

    /**
     * clear
     *
     * @return void
     */
    protected function clear()
    {
        if ($days = $this->option('clear')) {
            $clearDate = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

            $this->info("-> Sameday Api Status : Clearing entries older than: {$clearDate}");

            SamedayApiStatus::where('created_at', '<=', $clearDate)->delete();
        }
    }
}
