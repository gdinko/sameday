<?php

namespace Mchervenkov\Sameday\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;
use Mchervenkov\Sameday\Exceptions\SamedayException;
use Mchervenkov\Sameday\Exceptions\SamedayValidationException;
use Mchervenkov\Sameday\Hydrators\Locker;
use Mchervenkov\Sameday\Models\SamedayLocker;
use Mchervenkov\Sameday\Sameday;

class GetSamedayLockers extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sameday:get-lockers
                            {--country_code= : Filter lockers by country ISO code with 2 digits }
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Sameday API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Sameday API Lockers and saves it into the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('-> Sameday Lockers');

        try {

            $this->clear();

            $sameday = $this->initSamedayClient();

            $this->insertLockers($sameday);

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
     * @return void
     * @throws SamedayException
     * @throws ValidationException
     * @throws SamedayValidationException
     */
    protected function insertLockers(Sameday $sameday) : void
    {

        $response = $sameday->getLockers($this->initLockerHydrator());

        if (! empty($response)) {
            foreach ($response as $locker) {

                SamedayLocker::create($this->getLockerData($locker));
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

            $this->info("-> Sameday Lockers : Clearing entries older than: $clearDate");

            SamedayLocker::query()
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
     * @return Locker
     */
    private function initLockerHydrator(): Locker
    {
        return new Locker([
            'countryCode' => $this->option('country_code') ?? null
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function getLockerData(array $data): array
    {
        return [
            'locker_id' => data_get($data, 'lockerId'),
            'county_id' => data_get($data, 'countyId'),
            'county_name' => data_get($data, 'county'),
            'country_id' => data_get($data, 'countryId'),
            'country_name' => data_get($data, 'country'),
            'city_id' => data_get($data, 'cityId'),
            'city_name' => data_get($data, 'city'),
            'address' => data_get($data, 'address'),
            'postal_code' => data_get($data, 'postalCode'),
            'lat' => data_get($data, 'lat'),
            'lng' => data_get($data, 'lng'),
        ];
    }
}
