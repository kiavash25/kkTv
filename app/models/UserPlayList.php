<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UserPlayList extends Model
{
    protected $guarded = [];
    protected $table = 'userPlayLists';

    public function videos(){
        return $this->hasMany(Video::class, 'playListId', 'id')
                    ->orderBy('playListRow');
    }
}
