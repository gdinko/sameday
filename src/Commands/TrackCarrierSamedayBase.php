<?php

namespace Mchervenkov\Sameday\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Mchervenkov\Sameday\Exceptions\SamedayImportValidationException;
use Mchervenkov\Sameday\Hydrators\Awb;
use Mchervenkov\Sameday\Models\CarrierSamedayTracking;
use Mchervenkov\Sameday\Sameday;

abstract class TrackCarrierSamedayBase extends Command
{
    protected $parcels = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sameday:track
                            {--account= : Set Sameday API Account ex:user|password}
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=20 : Sameday API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track Sameday parcels';

    protected Sameday $sameday;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->sameday = new Sameday();

        $this->info('-> Carrier Sameday Parcel Tracking');

        try {
            $this->setAccount();

            $this->setup();

            $this->clear();

            $this->sameday->setTimeout(
                $this->option('timeout')
            );

            $this->track();

            $this->newLine(2);
        } catch (SamedayImportValidationException $eive) {
            $this->newLine();
            $this->error(
                $eive->getMessage()
            );
            $this->info(
                print_r($eive->getData(), true)
            );
            $this->error(
                print_r($eive->getErrors(), true)
            );
        } catch (\Exception $e) {
            $this->newLine();
            $this->error(
                $e->getMessage()
            );
        }

        return 0;
    }

    /**
     * setAccount
     *
     * @return void
     */
    protected function setAccount()
    {
        if ($this->option('account')) {
            $account = explode('|', $this->option('account'));
            $this->sameday->setAccount(
                $account[0],
                $account[1]
            );
        }
    }

    /**
     * setup
     *
     * @return void
     */
    abstract protected function setup();

    /**
     * clear
     *
     * @return void
     */
    protected function clear()
    {
        if ($days = $this->option('clear')) {
            $clearDate = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

            $this->info("-> Carrier Sameday Parcel Tracking : Clearing entries older than: {$clearDate}");

            CarrierSamedayTracking::where('created_at', '<=', $clearDate)->delete();
        }
    }

    /**
     * track
     *
     * @return void
     */
    protected function track()
    {
        $bar = $this->output->createProgressBar(
            count($this->parcels)
        );

        $bar->start();

        if (! empty($this->parcels)) {

            foreach ($this->parcels as $parcel) {
                $trackingInfo = $this->sameday->getAwbStatus(
                    new Awb(['number' => $parcel])
                );

                if (! empty($trackingInfo)) {
                    $this->processTracking($trackingInfo, $bar);
                }
            }

        }

        $bar->finish();
    }

    /**
     * processTracking
     *
     * @param  array $trackingInfo
     * @param  mixed $bar
     * @return void
     */
    protected function processTracking(array $trackingInfo, $bar)
    {
        CarrierSamedayTracking::updateOrCreate(
            [
                'parcel_id' => $trackingInfo['expeditionSummary']['awbNumber'],
            ],
            [
                'carrier_signature' => $this->sameday->getSignature(),
                'carrier_account' => $this->sameday->getUserName(),
                'meta' => $trackingInfo,
            ]
        );

        $bar->advance();
    }
}
