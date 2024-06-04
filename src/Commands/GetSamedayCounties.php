<?php

namespace Mchervenkov\Sameday\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Mchervenkov\Sameday\Exceptions\SamedayException;
use Mchervenkov\Sameday\Hydrators\County;
use Mchervenkov\Sameday\Hydrators\Paginator;
use Mchervenkov\Sameday\Models\SamedayCounty;
use Mchervenkov\Sameday\Sameday;

class GetSamedayCounties extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sameday:get-counties
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Sameday API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Sameday API Counties and saves it into the database';

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
        $this->info('-> Sameday Counties');

        try {

            $this->clear();

            $sameday = $this->initSamedayClient();
            $countyHydrator = $this->initCountyHydrator();
            $paginator = $this->initPaginator();

            $this->insertCounties($sameday, $countyHydrator, $paginator);

            $this->info('Status: ' . self::API_STATUS_OK);

        } catch (\Exception $e) {

            $this->newLine();
            $this->error('Status: ' . self::API_STATUS_NOT_OK);
            $this->error(
                $e->getMessage()
            );
        }

        return 0;
    }

    /**
     * @param Sameday $sameday
     * @param County $county
     * @param Paginator $paginator
     * @return void
     * @throws SamedayException
     */
    protected function insertCounties(Sameday $sameday, County $countyHydrator, Paginator $paginator) : void
    {
        $response = $sameday->getCounties($countyHydrator, $paginator);

        if (! empty($response['data'])) {
            foreach ($response['data'] as $county) {
                $county['country_id'] = $county['countryId'];
                $county['latin_name'] = $county['latinName'];
                $county['county_id'] = $county['id'];

                SamedayCounty::create($county);
            }

            $pages = data_get($response, 'pages');

            if($pages > 1 && $pages > $paginator->page) {
                $paginator->setPage($paginator->page + 1);
                $this->insertCounties($sameday, $countyHydrator, $paginator);
            }
        }

    }

    /**
     * clear
     *
     * @return void
     */
    private function clear()
    {
        if ($days = $this->option('clear')) {
            $clearDate = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

            $this->info("-> Sameday Counties : Clearing entries older than: $clearDate");

            SamedayCounty::query()
                ->where('created_at', '<=', $clearDate)
                ->delete();
        }
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
     * @return County
     */
    private function initCountyHydrator(): County
    {
        return new County();
    }

    /**
     * @return Paginator
     */
    private function initPaginator(): Paginator
    {
        return new Paginator();
    }
}
