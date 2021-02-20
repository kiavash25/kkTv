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
        $KOOCHITATV_NOUNC_CODE = config("app.KOOCHITATV_NOUNC_CODE");

        $nowTime = Carbon::now()->getTimestamp();
        $ck = $KOOCHITATV_NOUNC_CODE.'_'.$_GET['time'].'_'.$_GET['kind'].'_'.$_GET['id'];
        $checkHash = Hash::check($ck, $_GET['code']);

        if(($nowTime - $_GET['time']) > 2000)
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
            }
            else {
                $result = [];
                $video = Video::find($video->videoId);
                $video = getVideoFullInfo($video);

                $result = [
                    'url' => $video->url,
                    'seen' => $video->seen,
                    'like' => $video->like,
                    'disLike' => $video->disLike,
                    'pic' => $video->pic,
                    'title' => $video->title,
                    'username' => $video->username,
                    'userPic' => $video->userPic,
                    'time' => $video->time,
                ];
                return response()->json(['status' => 'ok', 'result' => $result]);
            }
        }
    }

    public function getNewestVideos()
    {
        $KOOCHITATV_NOUNC_CODE = config("app.KOOCHITATV_NOUNC_CODE");
        $nowTime = Carbon::now()->getTimestamp();
        $ck = $KOOCHITATV_NOUNC_CODE.'_'.$_GET['time'];
        $checkHash = Hash::check($ck, $_GET['code']);
        if(($nowTime - $_GET['time']) > 2000)
            return response()->json(['status' => 'outTime']);

        if($checkHash) {
            $result = [];
            $count = $_GET['count'];
            $videos = Video::youCanSee()->select(['id', 'userId', 'code', 'title', 'video', 'thumbnail', 'categoryId', 'seen', 'confirm', 'link', 'created_at'])
                            ->orderBy('created_at', 'DESC')->take($count)->get();

            foreach($videos as $video) {
                $video = getVideoFullInfo($video, false);
                array_push($result, [
                    'url' => $video->url,
                    'seen' => $video->seen,
                    'like' => $video->like,
                    'disLike' => $video->disLike,
                    'pic' => $video->pic,
                    'title' => $video->title,
                    'username' => $video->username,
                    'userPic' => $video->userPic,
                    'time' => $video->time,
                ]);
            }

            return response()->json(['status' => 'ok', 'result' => $result]);
        }
        else
            return response()->json(['status' => 'wrong']);
    }
}
