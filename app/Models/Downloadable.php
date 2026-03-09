<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Downloadable extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'category',
        'thumbnail_path',
        'is_published',
        'downloads',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($downloadable) {
            if (empty($downloadable->slug)) {
                $downloadable->slug = Str::slug($downloadable->title);
            }
        });
    }

    public function incrementDownloads()
    {
        $this->increment('downloads');
    }

    public function getFileSizeFormatted()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
