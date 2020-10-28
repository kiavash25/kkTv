<?php

namespace App\Http\Controllers;

use App\models\places\Cities;
use App\models\places\Place;
use App\models\places\State;
use App\models\Tags;
use App\models\Video;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getVideoPlaces()
    {
        $videoCode = $_GET['code'];
        $video = Video::where('code', $videoCode)->first();
        if($video != null){
            $places = $video->getPlaces();
            if($places['status'] == 'ok')
                return response()->json(['status' => 'ok', 'result' => $places['result']]);
            else
                return response()->json(['status' => 'connectionErr', 'result' => $places['result']]);
        }
        else
            return response()->json(['stats' => 'nok']);
    }

    public function getTags()
    {
        $tag = $_GET['tag'];
        $tags = [];
        $same = [];

        if (strlen($tag) != 0) {
            $similar = Tags::where('name', 'LIKE', '%' . $tag . '%')->where('name', '!=', $tag)->get();

            foreach ($similar as $t)
                array_push($tags, [
                    'name' => $t->name,
                    'id' => $t->id
                ]);

            $same = Tags::where('name', $tag)->first();
            if ($same == null)
                $same = 0;
            else {
                $same = [
                    'name' => $same->name,
                    'id' => $same->id
                ];
            }
        }

        return response()->json(['tags' => $tags, 'send' => $tag, 'same' => $same]);
    }

    public function totalPlaceSearch()
    {
        $searchResult = [];
        $filters = null;
        $value = $_GET['value'];

        if(isset($_GET['filter']))
            $filters = $_GET['filter'];

        if(isset($filters['kindPlaceId']) && $filters['kindPlaceId'] == 1)
            $kindPlaces = Place::whereNotNull('tableName')->get();
        else if(isset($filters['kindPlaceId']))
            $kindPlaces = Place::whereNotNull('tableName')->whereIn('id', $filters['kindPlaceId'])->get();
        else
            $kindPlaces = [];

        if(isset($filters['state']) && $filters['state'] == 1){
            $pl = State::where('name', 'LIKE', '%'.$value.'%')->get();
            foreach ($pl as $item)
                array_push($searchResult, [
                    'id' => $item->id,
                    'name' => $item->name,
                    'kind' => 'state',
                    'kindPlaceId' => 'state',
                ]);
        }

        if(isset($filters['city']) && $filters['city'] == 1){
            $pl = Cities::where('name', 'LIKE', '%'.$value.'%')->where('isVillage', 0)->get();
            foreach ($pl as $item)
                array_push($searchResult, [
                    'id' => $item->id,
                    'name' => $item->name,
                    'state' => $item->getState->name,
                    'kind' => 'city',
                    'kindPlaceId' => 'city',
                ]);
        }

        if(isset($filters['village']) && $filters['village'] == 1){
            $pl = Cities::where('name', 'LIKE', '%'.$value.'%')->where('isVillage', 1)->get();
            foreach ($pl as $item)
                array_push($searchResult, [
                    'id' => $item->id,
                    'name' => $item->name,
                    'state' => $item->getState->name,
                    'kind' => 'village',
                    'kindPlaceId' => 'city',
                ]);
        }

        foreach ($kindPlaces as $kind){
            $pl = \DB::table($kind->tableName)->where('name', 'LIKE', '%'.$value.'%')->select(['id', 'name', 'cityId'])->get();
            foreach ($pl as $item) {
                $city = Cities::find($item->cityId);
                array_push($searchResult, [
                    'id' => $item->id,
                    'name' => $item->name,
                    'city' => $city->name,
                    'state' => $city->getState->name,
                    'kind' => $kind->tableName,
                    'kindPlaceId' => $kind->id,
                ]);
            }
        }

        return response()->json(['status' => 'ok', 'result' => $searchResult]);
    }
}

