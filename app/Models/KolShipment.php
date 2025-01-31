<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KolShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'raw_tiktok_account_id',
        'kol_management_id',
        'shipping_provider_id',
        'warehouse_id',
        'shipment_number',
        'receiver_name',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'destination_address',
        'shipment_date',
        'status',
        'notes'
    ];

    public function rawTiktokAccount()
    {
        return $this->hasOne(RawTiktokAccount::class, 'id', 'raw_tiktok_account_id');
    }

    public function kolManagement()
    {
        return $this->hasOne(KolManagement::class, 'id', 'kol_management_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
    
}
