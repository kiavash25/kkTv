<?php

namespace App\models\localShops;

use App\models\Activity;
use App\models\LogModel;
use App\models\PhotographersPic;
use App\models\User;
use Illuminate\Database\Eloquent\Model;

class LocalShops extends Model
{
    protected $table = 'localShops';
    protected $connection = 'koochitaConnection';
}
