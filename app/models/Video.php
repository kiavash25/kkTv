<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Video'
 *
 * @property integer $id
 * @property string $link
 * @property string $code
 * @property boolean $confirm
 * @method static \Illuminate\Database\Query\Builder|\App\models\Videos whereConfirm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Videos whereCode($value)
 */


class Video extends Model
{
    protected $table = 'videos';

    public static function whereId($target) {
        return Video::find($target);
    }
}
