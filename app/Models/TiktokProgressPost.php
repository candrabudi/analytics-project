<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiktokProgressPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'raw_tiktok_account_id',
        'kol_management_id',
        'title',
        'link_post',
        'deadline',
        'date_post',
        'target_views',
        'views',
        'likes',
        'comments',
        'shares',
        'saves',
        'brief',
    ];

    public function rawTiktokAccount()
    {
        return $this->hasOne(RawTiktokAccount::class, 'id', 'raw_tiktok_account_id');
    }

    public function kolManagement()
    {
        return $this->hasOne(KolManagement::class, 'id', 'kol_management_id');
    }
}
