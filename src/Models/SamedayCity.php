<?php

namespace Mchervenkov\Sameday\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *  Mchervenkov\Sameday\Models\SamedayCity
 *
 * @property int $id
 * @property int $county_id
 * @property int $country_id
 * @property int $city_id
 * @property string $city_name
 * @property string $city_latin_name
 * @property string $city_postal_code
 * @property string $county_name
 * @property string $county_code
 * @property string $county_latin_name
 * @property string $country_name
 * @property string $country_code
 */
class SamedayCity extends Model
{
    use HasFactory;

    protected $table = 'sameday_cities';

    protected $fillable = [
        'county_id',
        'country_id',
        'city_id',
        'city_name',
        'city_latin_name',
        'city_postal_code',
        'county_name',
        'county_code',
        'county_latin_name',
        'country_name',
        'country_code',
    ];
}
