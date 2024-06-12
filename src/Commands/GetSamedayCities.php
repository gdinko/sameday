<?php

namespace Mchervenkov\Sameday\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Mchervenkov\Sameday\Exceptions\SamedayException;
use Mchervenkov\Sameday\Hydrators\City;
use Mchervenkov\Sameday\Hydrators\Paginator;
use Mchervenkov\Sameday\Models\SamedayCity;
use Mchervenkov\Sameday\Sameday;

class GetSamedayCities extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sameday:get-cities
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Sameday API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Sameday API Cities and saves it into the database';

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
        $this->info('-> Sameday Cities');

        try {

            $this->clear();

            $sameday = $this->initSamedayClient();
            $cityHydrator = $this->initCityHydrator();
            $paginator = $this->initPaginator();

            $this->insertCities($sameday, $cityHydrator, $paginator);

            $this->info('Status: ' . self::API_STATUS_OK);

        } catch (\Exception $e) {

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
     * @param Sameday $sameday
     * @param City $cityHydrator
     * @param Paginator $paginator
     * @return void
     * @throws SamedayException
     */
    protected function insertCities(Sameday $sameday, City $cityHydrator, Paginator $paginator) : void
    {
        $hasMorePages = true;

        while ($hasMorePages) {

            $response = $sameday->getCities($cityHydrator, $paginator);

            if (! empty($response['data'])) {
                foreach ($response['data'] as $city) {

                    SamedayCity::create($this->getCityData($city));
                }

                $pages = data_get($response, 'pages');

                if($pages > 1 && $pages > $paginator->page) {
                    $paginator->setPage($paginator->page + 1);
                } else {
                    $hasMorePages = false;
                }
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

            $this->info("-> Sameday Cities : Clearing entries older than: $clearDate");

            SamedayCity::query()
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
     * @return City
     */
    private function initCityHydrator(): City
    {
        return new City();
    }

    /**
     * @return Paginator
     */
    private function initPaginator(): Paginator
    {
        return new Paginator();
    }

    /**
     * @param array $data
     * @return array
     */
    private function getCityData(array $data): array
    {
        return [
            'county_id' => data_get($data, 'county.id'),
            'county_name' => data_get($data, 'county.name'),
            'county_code' => data_get($data, 'county.code'),
            'county_latin_name' => data_get($data, 'county.latinName'),
            'country_id' => data_get($data, 'country.id'),
            'country_name' => data_get($data, 'country.name'),
            'country_code' => data_get($data, 'country.code'),
            'city_id' => data_get($data, 'id'),
            'city_name' => data_get($data, 'name'),
            'city_latin_name' => data_get($data, 'latinName'),
            'city_postal_code' => data_get($data, 'postalCode'),
        ];
    }
}
