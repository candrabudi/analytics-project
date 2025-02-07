<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiktokInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'raw_tiktok_account_id',
        'kol_management_id',
        'bank_id',
        'account_name',
        'account_number',
        'file_upload',
        'amount',
        'status',
        'type'
    ];

    public function rawTiktokAccount()
    {
        return $this->hasOne(RawTiktokAccount::class, 'id', 'raw_tiktok_account_id');
    }

    public function kolManagement()
    {
        return $this->hasOne(KolManagement::class, 'id', 'kol_management_id');
    }
    
    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }
}
