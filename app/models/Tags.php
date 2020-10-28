<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $guarded = [];
    protected $table = 'tag';
    protected $connection = 'koochitaConnection';
    public $timestamps = false;
}
