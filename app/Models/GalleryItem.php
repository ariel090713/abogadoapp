<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'gallery_id',
        'title',
        'file_path',
        'thumbnail_path',
        'order',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
