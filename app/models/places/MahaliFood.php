<?php

namespace App\models\places;

use Illuminate\Database\Eloquent\Model;

class MahaliFood extends Model
{
    protected $connection = 'koochitaConnection';
    protected $table = 'mahaliFood';

    public function materials(){
        return $this->belongsToMany(FoodMaterial::class, 'foodMaterialRelations', 'mahaliFoodId', 'foodMaterialId')
                    ->withPivot('volume');
    }
}
