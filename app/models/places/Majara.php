<?php

namespace App\models\places;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Majara'
 *
 * @property integer $id
 * @property string $name
 */

class Majara extends Model {
    protected $connection = 'koochitaConnection';
    protected $table = 'majara';
    public $timestamps = false;


    public static function whereId($value) {
        return Majara::find($value);
    }
}
