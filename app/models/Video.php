<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    public static function whereId($target) {
        return Video::find($target);
    }
}
