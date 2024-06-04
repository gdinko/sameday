<?php

namespace Mchervenkov\Sameday\Actions;

use Mchervenkov\Sameday\Exceptions\SamedayException;
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
}
