<?php

namespace Mchervenkov\Sameday\Hydrators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mchervenkov\Sameday\Exceptions\SamedayValidationException;

class Locker
{
    /**
     * Locker request data
     *
     * @var array
     */
    private array $data;

    /**
     * __construct
     *
     * @param  array $data
     * @return void
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * validated
     *
     * @return array
     * @throws SamedayValidationException
     * @throws ValidationException
     */
    public function validated(): array
    {
        $validator = Validator::make(
            $this->data,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new SamedayValidationException(
                __CLASS__,
                422,
                $validator->messages()->toArray()
            );
        }

        return $validator->validated();
    }

    /**
     * validationRules
     *
     * @return array
     */
    private function validationRules(): array
    {
        return [
            'lockerList' => 'nullable|string',
            'countryCode' => 'nullable|string',
            'locale' => 'nullable|string',
        ];
    }
}
