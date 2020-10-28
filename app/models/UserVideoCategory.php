<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UserVideoCategory extends Model
{
    protected $table = 'userVideoCategories';

    public function videos(){
        return $this->belongsToMany(Video::class, 'userVideoCategoryRelations', 'categoryId', 'videoId');
    }
}
