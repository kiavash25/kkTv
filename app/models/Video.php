<?php

namespace App\models;

use App\models\places\Cities;
use App\models\places\Place;
use App\models\places\State;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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
    protected $guarded = [];
    protected $connection = 'mysql';
    protected $table = 'videos';

    public static function whereId($target) {
        return Video::find($target);
    }

    public function playList(){
        return $this->belongsTo(UserPlayList::class, 'playListId', 'id');
    }

    public function mainCategory()
    {
        return $this->belongsToMany(VideoCategory::class, 'videoCategoryRelations', 'videoId', 'categoryId')
                    ->withPivot('isMain')->where('isMain', 1);
    }

    public function getPlaces()
    {
        $nouns = env('KOOCHITATV_NOUNC_CODE');
        $time = Carbon::now()->getTimestamp();
        $hash = Hash::make($nouns.'_'.$time);

        $states = [];
        $cities = [];
        $places = [];
        $placesRel = $this->hasMany(VideoPlaceRelation::class, 'videoId', 'id')->get();
        foreach ($placesRel as $place){
            if($place->kind == 'state')
                array_push($states, $place->placeId);
            else if($place->kind == 'city')
                array_push($cities, $place->placeId);
            else{
                array_push($places, [
                    'kindPlaceId' => $place->kindPlaceId,
                    'id' => $place->placeId,
                ]);
            }
        }

        $response = Http::get(env("KOOCHITA_URL_API").'/getPlacesForKoochitaTv', [
            'time' => $time,
            'code' => $hash,
            'state' => json_encode($states),
            'city' => json_encode($cities),
            'places' => json_encode($places)
        ]);

        if($response->status() == 200)
            return ['status' => 'ok', 'result' => json_decode($response->body())];
        else
            return ['status' => 'nok', 'result' => $response->status()];
    }

}
