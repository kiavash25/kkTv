<?php

namespace App\models\places;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Place'
 *
 * @property integer $id
 * @property string $name
 * @property string $fileName
 * @property string $tableName
 * @property boolean $visibility
 * @method static \Illuminate\Database\Query\Builder|\App\models\Place whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Place whereVisibility($value)
 */

class Place extends Model {
    protected $connection = 'koochitaConnection';
    protected $table = 'place';
    public $timestamps = false;


    public static function whereId($value) {
        return Place::find($value);
    }
}
