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

    public function assignCategory()
    {
        return $this->hasMany(AssignTiktokCategory::class, 'raw_tiktok_account_id', 'raw_tiktok_account_id')
            ->join('tiktok_categories as tc', 'tc.id', '=', 'assign_tiktok_categories.tiktok_category_id');
    }
}
