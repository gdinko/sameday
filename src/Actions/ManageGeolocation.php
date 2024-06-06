<?php

namespace Mchervenkov\Sameday\Actions;

use Illuminate\Validation\ValidationException;
use Mchervenkov\Sameday\Exceptions\SamedayException;
use Mchervenkov\Sameday\Exceptions\SamedayValidationException;
use Mchervenkov\Sameday\Hydrators\City;
use Mchervenkov\Sameday\Hydrators\County;
use Mchervenkov\Sameday\Hydrators\Paginator;

trait ManageGeolocation
{
    /**
     * Listing the counties
     *
     * The documentation is available at GET/api/geolocation/county
     *
     * @param County|null $county
     * @param Paginator|null $paginator
     * @return mixed
     * @throws SamedayException
     */
    public function getCounties(County $county = null, Paginator|null $paginator = null): mixed
    {
        return $this->get(
            'api/geolocation/county',
            array_merge(['name' => optional($county)->name], $this->getPaginationData($paginator))
        );
    }

    /**
     * Listing the cities
     *
     * The documentation is available at GET/api/geolocation/city
     *
     * @param City|null $city
     * @param Paginator|null $paginator
     * @return mixed
     * @throws SamedayException
     * @throws ValidationException
     * @throws SamedayValidationException
     */
    public function getCities(City $city = null, Paginator|null $paginator = null): mixed
    {
        $cityData = $city ? $city->validated() : [];

        return $this->get(
            'api/geolocation/city',
            array_merge($cityData, $this->getPaginationData($paginator))
        );
    }
}
