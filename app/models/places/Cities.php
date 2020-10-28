<?php

namespace App\models\places;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Cities'
 *
 * @property integer $id
 * @property string $name
 * @property integer $stateId
 * @property float $x
 * @property float $y
 * @method static \Illuminate\Database\Query\Builder|\App\models\Cities whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Cities whereStateId($value)
 */

class Cities extends Model {

    protected $connection = 'koochitaConnection';
    protected $table = 'cities';
    public $timestamps = false;

    public static function whereId($value) {
        return Cities::find($value);
    }

    public function getState()
    {
        return $this->belongsTo(State::class, 'stateId', 'id');
    }
}
