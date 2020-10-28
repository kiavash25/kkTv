<?php

namespace App\models\places;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Amaken'
 *
 * @property integer $id
 * @property string $name
 */

class Amaken extends Model {

    protected $connection = 'koochitaConnection';
    protected $table = 'amaken';
    public $timestamps = false;

    public static function whereId($value) {
        return Amaken::find($value);
    }
}
