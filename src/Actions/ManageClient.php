<?php

namespace Mchervenkov\Sameday\Actions;

use Illuminate\Validation\ValidationException;
use Mchervenkov\Sameday\Exceptions\SamedayException;
use Mchervenkov\Sameday\Exceptions\SamedayValidationException;
use Mchervenkov\Sameday\Hydrators\Awb;
use Mchervenkov\Sameday\Hydrators\Paginator;

trait ManageClient
{
    use HasPagination;

    /**
     * The Sameday available services for the client, once the client has logged in,
     * can be viewed by calling the endpoint GET/api/client/services;
     *
     * @param Paginator|null $paginator
     * @return mixed
     * @throws SamedayException
     */
    public function getServices(Paginator $paginator = null): mixed
    {
        return $this->get(
            'api/client/services',
            $this->getPaginationData($paginator)
        );
    }

    /**
     * The endpoint used for obtaining these ids is GET/api/client/pickup-points;
     *
     * If the client hasn’t defined a pick-up point yet or if the defined pick-up point is not the one the client wants, a new
     * pick-up point can be defined using the endpoint POST/api/client/pickup-points or making a request to the Sameday
     * technical support team
     *
     * @param Paginator|null $paginator
     * @return mixed
     * @throws SamedayException
     */
    public function getPickupPoints(Paginator $paginator = null): mixed
    {
        return $this->get(
            'api/client/pickup-points',
            $this->getPaginationData($paginator)
        );
    }

    /**
     * Obtaining the status by calling the endpoint GET/api/client/awb/{awbNumber}/status:
     *
     * @param Awb $awb
     * @return mixed
     * @throws SamedayException
     * @throws ValidationException
     * @throws SamedayValidationException
     */
    public function getAwbStatus(Awb $awb): mixed
    {
        $awbNumber = data_get($awb->validated(), 'number');

        return $this->get(
            "api/client/awb/$awbNumber/status"
        );
    }

    /**
     * Sync the status by calling the endpoint GET/api/client/status-sync
     *
     * @param Awb|null $awb
     * @param Paginator|null $paginator
     * @return mixed
     * @throws SamedayException
     * @throws SamedayValidationException
     * @throws ValidationException
     */
    public function syncStatus(Awb $awb = null, Paginator $paginator = null): mixed
    {
        $awbData = $awb ? $awb->validated() : [];

        return $this->get(
            "api/client/awb/status-sync",
            array_merge($awbData, $this->getPaginationData($paginator))
        );
    }
}
