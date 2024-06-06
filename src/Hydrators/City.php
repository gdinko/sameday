<?php

namespace Mchervenkov\Sameday\Hydrators;

use Illuminate\Validation\ValidationException;
use Mchervenkov\Sameday\Exceptions\SamedayValidationException;
use Illuminate\Support\Facades\Validator;

class City
{
    /**
     * City request data
     *
     * @var array
     */
    private array $city;

    /**
     * __construct
     *
     * @param  array $city
     * @return void
     */
    public function __construct(array $city = [])
    {
        $this->city = $city;
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
            $this->city,
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
            'name' => 'nullable|string',
            'postalCode' => 'nullable|string',
            'countyCode' => 'nullable|string',
        ];
    }
}
