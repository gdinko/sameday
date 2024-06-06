<?php

namespace Mchervenkov\Sameday\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *  Mchervenkov\Sameday\Models\SamedayLocker
 *
 * @property int $id
 * @property int $locker_id
 * @property int $county_id
 * @property int $country_id
 * @property int $city_id
 * @property string $city_name
 * @property string $county_name
 * @property string $country_name
 * @property string $address
 * @property string $postal_code
 * @property string $lat
 * @property string $lng
 */
class SamedayLocker extends Model
{
    use HasFactory;

    protected $table = 'sameday_lockers';

    protected $fillable = [
        'locker_id',
        'county_id',
        'country_id',
        'city_id',
        'city_name',
        'county_name',
        'country_name',
        'address',
        'postal_code',
        'lat',
        'lng',
    ];
}