<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoPlaceRelation extends Model
{

    protected $table = 'videoPlaceRelations';
    protected $connection = 'mysql';
    public $timestamps = false;
}
