<?php

namespace App\Http\Middleware;

use App\models\VideoCategory;
use App\models\VideoLive;
use Carbon\Carbon;
use Closure;
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
        $vodCategory = VideoCategory::where('parent', 0)->get();
        foreach ($vodCategory as $cat){
            $cat->sub = VideoCategory::where('parent', $cat->id)->get();
            foreach ($cat->sub as $item){
                $item->onIcon = \URL::asset('_images/video/category/'. $item->onIcon);
                $item->offIcon = \URL::asset('_images/video/category/'. $item->offIcon);
            }
        }

        date_default_timezone_set('Asia/Tehran');
        $userPicture = null;
        if(auth()->check())
            $userPicture = getUserPic(auth()->user()->id);
        else
            $userPicture = getUserPic(0);

        $today = Carbon::now()->format('Y-m-d');
        $nowTime = Carbon::now()->format('H:i');

        $lives = VideoLive::where('isLive', 1)->orderBy('sDate')->orderBy('sTime')->first();
        if($lives != null && ($lives->sDate < $today || ($lives->sDate == $today && $lives->sTime <= $nowTime)))
            $hasLive = $lives->code;
        else
            $hasLive = false;

        View::share(['vodCategory' => $vodCategory, 'userPicture' => $userPicture, 'hasLive' => $hasLive]);

        return $next($request);
    }
}
