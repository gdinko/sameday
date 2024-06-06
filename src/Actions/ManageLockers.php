<?php

namespace Mchervenkov\Sameday\Actions;

use Illuminate\Validation\ValidationException;
use Mchervenkov\Sameday\Exceptions\SamedayException;
use Mchervenkov\Sameday\Exceptions\SamedayValidationException;
use Mchervenkov\Sameday\Hydrators\Locker;

trait ManageLockers
{
    /**
     * The endpoint that returns the easybox lockers list is GET/api/locker/lockers.
     *
     * @param Locker|null $locker
     * @return mixed
     * @throws ValidationException
     * @throws SamedayException
     * @throws SamedayValidationException
     */
    public function getLockers(Locker $locker = null): mixed
    {
        $lockerData = $locker ? $locker->validated() : [];

        return $this->get(
            'api/locker/lockers',
            $lockerData
        );
    }
}
