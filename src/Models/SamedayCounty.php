<?php

namespace Mchervenkov\Sameday\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Sameday\Models\SamedayCounty
 *
 * @property int $id
 * @property int $county_id
 * @property int $country_id
 * @property string $country
 * @property string $name
 * @property string $code
 * @property string $latin_name
 */
class SamedayCounty extends Model
{
    use HasFactory;

    protected $table = 'sameday_counties';

    protected $fillable = [
        'county_id',
        'country_id',
        'country',
        'name',
        'code',
        'latin_name',
    ];
}
