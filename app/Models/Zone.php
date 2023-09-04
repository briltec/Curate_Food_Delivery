<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use App\Scopes\ZoneScope;

class Zone extends Model
{
    use HasFactory;
    use SpatialTrait;

    protected $casts = [
        'id'=>'integer',
        'status'=>'integer',
        'minimum_shipping_charge'=>'float',
        'maximum_shipping_charge'=>'float',
        'per_km_shipping_charge'=>'float',
        'max_cod_order_amount'=>'float',
        'increased_delivery_fee'=>'float',
        'increased_delivery_fee_status'=>'integer',
    ];

    protected $spatialFields = [
        'coordinates'
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function deliverymen()
    {
        return $this->hasMany(DeliveryMan::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Restaurant::class);
    }


    public function campaigns()
    {
        return $this->hasManyThrough(Campaigns::class, Restaurant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }
    public function incentives()
    {
        return $this->hasMany(Incentive::class)->orderBy('earning');
    }

    public function incentive_logs()
    {
        return $this->hasMany(IncentiveLog::class);
    }
}
