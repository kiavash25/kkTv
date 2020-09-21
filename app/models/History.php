<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'History'
 *
 * @property integer $id
 * @property integer $server_id
 * @property integer $video_id
 * @property string $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\models\History whereServerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\History whereVideoId($value)
 */

class History extends Model {

    protected $table = 'history';
    public $timestamps = false;

    public static function whereId($value) {
        return History::find($value);
    }
}

