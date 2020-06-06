<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'RetrievePas'
 *
 * @property integer $id
 * @property string $sendTime
 * @property integer $uId
 * @method static \Illuminate\Database\Query\Builder|\App\models\RetrievePas whereUId($value)
 */

class RetrievePas extends Model {

    protected $table = 'retrievePas';
    public $timestamps = false;
}
