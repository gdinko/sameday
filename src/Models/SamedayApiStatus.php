<?php

namespace Mchervenkov\Sameday\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Sameday\Models\SamedayApiStatus
 *
 * @property int $id
 * @property int $code
 * @method static Builder|CarrierCityMap create(array $attributes)
 * @method static Builder|CarrierCityMap where($column, $operator = null, $value = null, $boolean = 'and')
 */
class SamedayApiStatus extends Model
{
    protected $table = 'sameday_api_statuses';

    protected $fillable = [
        'code',
    ];
}
