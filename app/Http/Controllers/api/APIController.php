<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\models\Video;
use App\models\VideoPlaceRelation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class APIController extends Controller
{
    public function videoForPlaces()
    {
        $nowTime = Carbon::now()->getTimestamp();
        $ck = env("KOOCHITATV_NOUNC_CODE").'_'.$_GET['time'];
        $checkHash = Hash::check($ck, $_GET['code']);

        if(($nowTime - $_GET['time']) > 1000)
            return response()->json(['status' => 'outTime']);

        if($checkHash) {
            $kind = $_GET['kind'];
            $placeId = $_GET['id'];

            if ($kind == 'state' || $kind == 'city') {
                $video = VideoPlaceRelation::where('kind', $kind)->where('placeId', $placeId)->where('choosed', 1)->first();
                if ($video == null)
                    $video = VideoPlaceRelation::where('kind', $kind)->where('placeId', $placeId)->first();
            } else {
                $video = VideoPlaceRelation::where('kindPlaceId', $kind)->where('placeId', $placeId)->where('choosed', 1)->first();
                if ($video == null) {
                    $video = VideoPlaceRelation::where('kindPlaceId', $kind)->where('placeId', $placeId)->first();
                    if ($video == null)
                        $video = VideoPlaceRelation::where('kindPlaceId', $kind)->first();
                }
            }

            if ($video == null) {
                $video = VideoPlaceRelation::where('kind', 0)->where('choosed', 1)->first();
                if ($video == null)
                    return response()->json(['status' => 'notFoundVideo']);
            } else {
                $video = Video::find($video->videoId);
                $video = getVideoFullInfo($video);
                return response()->json(['status' => 'ok', 'result' => $video]);
            }
        }
    }
}
