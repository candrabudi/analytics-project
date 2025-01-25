<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiktokCategory extends Model
{
    use HasFactory;

    public function rawTiktokAccounts()
    {
        return $this->belongsToMany(RawTiktokAccount::class, 'assign_tiktok_categories');
    }
    
}
