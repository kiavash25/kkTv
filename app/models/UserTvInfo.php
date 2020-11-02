<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UserTvInfo extends Model
{
    protected $connection = 'mysql';
    protected $table = 'userTvInfos';
    public $timestamps = false;
}
