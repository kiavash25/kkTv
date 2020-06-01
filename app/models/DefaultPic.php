<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'DefaultPic'
 *
 * @property integer $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\DefaultPic whereName($value)
 */


class DefaultPic extends Model {

    protected $table = 'defaultPic';
    public $timestamps = false;


    public static function whereId($value) {
        return DefaultPic::find($value);
    }
}
