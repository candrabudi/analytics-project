<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KolManagement extends Model
{
    use HasFactory;

    public function rawTiktokAccount()
    {
        return $this->hasOne(RawTiktokAccount::class, 'id', 'raw_tiktok_account_id');
    }
}
