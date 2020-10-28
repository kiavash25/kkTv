<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    protected $table = 'videoCategories';
    public $timestamps = false;

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'videoCategoryRelations', 'categoryId', 'videoId');
    }
}
