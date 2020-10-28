<?php

namespace App\models\places;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'State'
 *
 * @property integer $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\State whereName($value)
 */

class State extends Model {

    protected $table = 'state';
    public $timestamps = false;
    protected $connection = 'koochitaConnection';


    public static function whereId($value) {
        return State::find($value);
    }

    public function getCities()
    {
        return $this->hasMany(Cities::class, 'stateId', 'id');
    }
}
