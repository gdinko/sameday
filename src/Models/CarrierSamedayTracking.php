<?php

namespace Mchervenkov\Sameday\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Sameday\Models\CarrierSamedayTracking
 *
 * @property int $id
 * @property int $parcel_id
 * @property string $carrier_signature
 * @property string $carrier_account
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|CarrierSamedayTracking newModelQuery()
 * @method static Builder|CarrierSamedayTracking newQuery()
 * @method static Builder|CarrierSamedayTracking query()
 * @method static Builder|CarrierSamedayTracking whereCarrierAccount($value)
 * @method static Builder|CarrierSamedayTracking whereCarrierSignature($value)
 * @method static Builder|CarrierSamedayTracking whereCreatedAt($value)
 * @method static Builder|CarrierSamedayTracking whereId($value)
 * @method static Builder|CarrierSamedayTracking whereMeta($value)
 * @method static Builder|CarrierSamedayTracking whereParcelId($value)
 * @method static Builder|CarrierSamedayTracking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierSamedayTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_id',
        'carrier_signature',
        'carrier_account',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
