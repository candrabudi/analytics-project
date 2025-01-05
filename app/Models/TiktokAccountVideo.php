<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiktokAccountVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'tiktok_account_id',
        'aweme_id',
        'video_id',
        'region',
        'title',
        'cover',
        'duration',
        'play',
        'play_count',
        'digg_count',
        'comment_count',
        'share_count',
        'download_count',
        'collect_count',
        'create_time',
        'is_top'
    ];
}
