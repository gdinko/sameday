<?php

namespace Mchervenkov\Sameday\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Sameday\Models\SamedayApiStatus
 *
 * @property int $id
 * @property int $code
 */
class SamedayApiStatus extends Model
{
    protected $table = 'sameday_api_statuses';

    protected $fillable = [
        'code',
    ];
}
