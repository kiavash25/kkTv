<?php

namespace App\Http\Middleware;

use App\models\Live;
use App\models\UserPlayList;
use App\models\UserVideoCategory;
use App\models\VideoCategory;
use App\models\VideoLive;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class vodShareData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $fileVersion = 3;

        $vodCategory = VideoCategory::where('parent', 0)->get();
        foreach ($vodCategory as $cat){
            $cat->sub = VideoCategory::where('parent', $cat->id)->get();
            foreach ($cat->sub as $item){
                $item->onIcon = \URL::asset('images/video/category/'. $item->onIcon);
                $item->offIcon = \URL::asset('images/video/category/'. $item->offIcon);
            }
        }

        $userPicture = getUserPic(auth()->check() ? auth()->user()->id : 0);

        $today = Carbon::now()->format('Y-m-d');
        $nowTime = Carbon::now()->format('H:i');

        $hasLive = false;
        $timeToLive = null;
        $timeToLiveCode = false;

        $lives = Live::where('isLive', 1)->orderBy('sDate')->orderBy('sTime')->first();
        if($lives != null) {
            $hasLive = $lives->code;
            if(($lives->sDate < $today || ($lives->sDate == $today && $lives->sTime <= $nowTime)))
                $timeToLiveCode = $lives->code;
            else if($lives->sDate == $today){
                $timeToLive = $lives->sTime . ':00';
                $timeToLiveCode = $lives->code;
            }
        }


        View::share(['vodCategory' => $vodCategory, 'userPicture' => $userPicture, 'timeToLive' => $timeToLive, 'timeToLiveCode' => $timeToLiveCode, 'hasLive' => $hasLive, 'fileVersion' => $fileVersion]);

        return $next($request);
    }
}
