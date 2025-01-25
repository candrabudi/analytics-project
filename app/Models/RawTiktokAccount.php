<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RawTiktokAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'unique_id',
        'nickname',
        'follower',
        'following',
        'like',
        'total_video',
        'avg_views',
        'total_interactions',
        'status_call',
        'whatsapp_number',
        'notes',
        'file',
    ];

    // Accessor untuk file URL
    public function getFileUrlAttribute()
    {
        if ($this->file) {
            return url(Storage::url('files/' . $this->file)); // Menghasilkan URL lengkap dari file
        }

        return null;
    }

    public function assignCategory()
    {
        return $this->hasMany(AssignTiktokCategory::class, 'raw_tiktok_account_id', 'id')
            ->join('tiktok_categories as tc', 'tc.id', '=', 'assign_tiktok_categories.tiktok_category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(TiktokCategory::class, 'assign_tiktok_categories');
    }
    
}
