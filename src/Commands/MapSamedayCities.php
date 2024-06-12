<?php

namespace Mchervenkov\Sameday\Commands;

use Illuminate\Console\Command;
use Mchervenkov\Sameday\Exceptions\SamedayImportValidationException;
use Mchervenkov\Sameday\Models\CarrierCityMap;
use Mchervenkov\Sameday\Models\SamedayCity;
use Mchervenkov\Sameday\Models\SamedayLocker;
use Mchervenkov\Sameday\Sameday;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class MapSamedayCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sameday:map-cities
                            {country_code : Country ALPHA 2 ISO 3166 code}
                            {--timeout=20 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Sameday cities and makes carriers city map in database';

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
        $this->info('-> Sameday Map Cities');

        try {

            $sameday = $this->initSamedayClient();

            $this->import($sameday);

            $this->newLine(2);

        } catch (\Exception $e) {
            $this->newLine();
            $this->error(
                $e->getMessage()
            );

            return 1;
        }

        return 0;
    }

    /**
     * import
     *
     * @return void
     */
    protected function import(Sameday $sameday)
    {
        $countryCode = $this->argument('country_code');

        /** @var SamedayCity $city */
        $city = SamedayCity::query()
            ->where(
                'country_code',
                $countryCode
            )->firstOrFail();

        $cities = SamedayCity::all();

        if (!SamedayLocker::query()->where('country_id', $city->country_id)->count()) {
            $this->newLine();
            $this->warn('[WARN] Import Sameday Lockers first to map locker city ...');
            $this->newLine();
        }

        $bar = $this->output->createProgressBar(
            count($cities)
        );

        $bar->start();

        if ($cities->isNotEmpty()) {
            CarrierCityMap::where(
                'carrier_signature',
                $sameday->getSignature()
            )
            ->where('country_code', strtoupper($countryCode))
            ->delete();

            foreach ($cities as $city) {
                try {

                    $name = $this->normalizeCityName(
                        $city->city_name
                    );

                    $nameSlug = $this->getSlug($name);

                    $slug = $this->getSlug(
                        $nameSlug . ' ' . $city->city_postal_code
                    );

                    $data = [
                        'carrier_signature' => $sameday->getSignature(),
                        'carrier_city_id' => $city->city_id,
                        'country_code' => $city->country_code,
                        'region' => Str::title($city->county_name),
                        'name' => $name,
                        'name_slug' => $nameSlug,
                        'post_code' => $city->city_postal_code,
                        'slug' => $slug,
                        'uuid' => $this->getUuid($slug),
                    ];

                    CarrierCityMap::create(
                        $data
                    );

                    //set city_uuid to all locker with this city_id
                    SamedayLocker::query()
                        ->where(
                            'city_id',
                            $city->city_id
                        )->update([
                            'city_uuid' => $data['uuid'],
                        ]);
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
                }

                $bar->advance();
            }
        }

        $bar->finish();
    }

    /**
     * validationRules
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'id' => 'integer|required',
            'country' => 'array',
            'country.code3' => 'string|required',
            'postCode' => 'string|nullable',
            'name' => 'string|required',
            'nameEn' => 'string|nullable',
            'regionName' => 'string|nullable',
            'regionNameEn' => 'string|nullable',
            'phoneCode' => 'string|required',
        ];
    }

    /**
     * normalizeCityName
     *
     * @param  string $name
     * @return string
     */
    protected function normalizeCityName(string $name): string
    {
        return Str::title(
            explode(',', $name)[0]
        );
    }

    /**
     * getSlug
     *
     * @param  string $string
     * @return string
     */
    protected function getSlug(string $string): string
    {
        return Str::slug($string);
    }

    /**
     * getUuid
     *
     * @param  string $string
     * @return string
     */
    protected function getUuid(string $string): string
    {
        return Uuid::uuid5(
            Uuid::NAMESPACE_URL,
            $string
        )->toString();
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
}
