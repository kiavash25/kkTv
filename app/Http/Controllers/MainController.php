<?php

namespace App\Http\Controllers;

use App\models\logs\UserScrollLog;
use App\models\Tags;
use App\models\UserEventRegistr;
use App\models\UserSeenLog;
use App\models\Video;
use App\models\VideoBookMark;
use App\models\VideoCategory;
use App\models\VideoComment;
use App\models\VideoFeedback;
use App\models\VideoLimbo;
use App\models\Live;
use App\models\LiveChat;
use App\models\LiveFeedBack;
use App\models\LiveGuest;
use App\models\VideoPlaceRelation;
use App\models\VideoTagRelation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Throwable;

class MainController extends Controller
{
    public function indexStreaming()
    {
        $confirmConditions = ['state' => 1, 'confirm' => 1];
        $lastVideos = Video::where($confirmConditions)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        foreach ($lastVideos as $lvid)
            $lvid = getVideoFullInfo($lvid, false);

        $videoCategory = VideoCategory::where('parent', 0)->get();
        foreach ($videoCategory as $vic) {
            $catId = VideoCategory::where('parent', $vic->id)->pluck('id')->toArray();
            $vic->video = Video::where($confirmConditions)->whereIn('categoryId', $catId)->take(10)->orderByDesc('created_at')->get();
            foreach ($vic->video as $catVid)
                $catVid = getVideoFullInfo($catVid, false);
        }

        $topId = [];
        $topVideosId = \DB::select('SELECT videoId, COUNT(id) as likeCount FROM videoFeedbacks WHERE commentId IS NULL AND `like` = 1 GROUP BY videoId ORDER BY likeCount DESC LIMIT 10');
        foreach ($topVideosId as $item)
            array_push($topId, $item->videoId);

        $topVideos = Video::whereIn('id', $topId)->get();
        foreach ($topVideos as $item)
            $item = getVideoFullInfo($item, false);

        $registerInCarpet = false;
        if(\auth()->check())
            $registerInCarpet = UserEventRegistr::where('userId', \auth()->user()->id)->where('event', 'carpet')->count() == 1;
//            $registerInCarpet = UserEventRegistr::where('userId', \auth()->user()->id)->where('event', 'carpet')->count() != 1;

        return view('mainPage', compact(['lastVideos', 'videoCategory', 'topVideos', 'registerInCarpet']));
    }

    public function videoList($kind, $value){
        if($kind == 'category'){
            $category = VideoCategory::find($value);
            if($category == null)
                return redirect(route('index'));
            else{
                if($category->parent == 0) {
                    $confirmConditions = ['state' => 1, 'confirm' => 1];

                    $category->subs = VideoCategory::where('parent', $category->id)->get();
                    foreach ($category->subs as $item)
                        $item->icon = \URL::asset('images/video/category/'.$item->offIcon);

                    $subsId = VideoCategory::where('parent', $category->id)->pluck('id')->toArray();
                    $category->lastVideo = Video::where($confirmConditions)->whereIn('categoryId', $subsId)->take(10)->orderByDesc('created_at')->get();
                    foreach ($category->lastVideo  as $catVid)
                        $catVid = getVideoFullInfo($catVid, false);

                    foreach ($category->subs as $item){
                        $item->video = Video::where($confirmConditions)->where('categoryId', $item->id)->take(10)->orderByDesc('created_at')->get();
                        $item->totalCount = Video::where($confirmConditions)->where('categoryId', $item->id)->count();
                        foreach ($item->video as $catVid)
                            $catVid = getVideoFullInfo($catVid, false);
                    }

                    if($category->onIcon != null)
                        $category->icon = \URL::asset('images/video/category/' . $category->onIcon);

                    if($category->banner != null)
                        $category->banner = \URL::asset('images/video/category/' . $category->banner);
                    else
                        $category->banner = \URL::asset('images/mainPics/defaultBanner.jpg');
                }
                else {
                    $category->icon = \URL::asset('images/video/category/' . $category->onIcon);
                    $mainCat = VideoCategory::find($category->parent);

                    if($category->banner == null) {
                        if ($mainCat->banner != null)
                            $category->banner = \URL::asset('images/video/category/' . $mainCat->banner);
                        else
                            $category->banner = \URL::asset('images/mainPics/defaultBanner.jpg');
                    }
                    else
                        $category->banner = \URL::asset('images/video/category/' . $category->banner);

                }

                $content = $category;
                return view('page.videoList', compact(['kind', 'value', 'content']));

            }
        }
        return view('page.videoList', compact(['kind', 'value']));
    }

    public function getVideoListElems(Request $request)
    {
        $kind = $request->kind;
        $value = $request->value;
        $perPage = $request->perPage;
        $page = $request->page;

        $confirmConditions = ['state' => 1, 'confirm' => 1];

        if($kind == 'category'){
            $category = VideoCategory::find($value);

            if($category != null){
                if($category->parent == 0)
                    $catId = VideoCategory::where('parent', $category->id)->pluck('id')->toArray();
                else
                    $catId = [$category->id];

                $videos = Video::where($confirmConditions)->whereIn('categoryId', $catId)->skip(($page - 1) * $perPage)->take($perPage)->orderByDesc('created_at')->get();
                foreach ($videos as $item)
                    $item = getVideoFullInfo($item, false);

                echo json_encode(['status' => 'ok', 'videos' => $videos]);
            }
            else
                echo json_encode(['status' => 'nok']);
            return;
        }
    }

    public function search(Request $request)
    {
        if(isset($request->value)){
            $videos = Video::where('title', 'LIKE', '%' . $request->value . '%')
                ->where(['state' => 1, 'confirm' => 1])
                ->select(['id', 'title', 'code', 'categoryId'])
                ->get();

            foreach ($videos as $item) {
                $item->category = VideoCategory::find($item->categoryId)->name;
                $item->url = route('video.show', ['code' => $item->code]);
            }
            echo json_encode(['status' => 'ok', 'num' => $request->num, 'result' => $videos]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function showVideo(Request $request, $code)
    {
        $video = Video::whereCode($code)->first();

        if ($video == null)
            return redirect(route('index'));

        $uId = 0;
        if (auth()->check())
            $uId = auth()->user()->id;

        $isLink = true;

        if (($video->confirm == 1 && $video->state == 1 && $video->link != null) || ($video->userId == $uId)) {

            if (!\Cookie::has('video_' . $video->code)) {
                \Cookie::queue(\Cookie::make('video_' . $video->code, 1, 5));
                $video->seen++;
                $video->save();
            }
            $video = getVideoFullInfo($video, true);

            $userMoreVideo = Video::where('userId', $video->userId)->where('confirm', 1)->where('state', 1)->where('id', '!=', $video->id)->take(4)->orderByDesc('created_at')->get();
            $sameCategory = Video::where('categoryId', $video->categoryId)->where('confirm', 1)->where('state', 1)->where('id', '!=', $video->id)->take(7)->orderByDesc('created_at')->get();

            foreach ([$userMoreVideo, $sameCategory] as $categ)
                foreach ($categ as $vid)
                    $vid = getVideoFullInfo($vid, false);

            if($video->userId == $uId && $video->link == null) {
                $video->link = URL::asset('videos/' . $video->userId . '/' . $video->video);
                $isLink = false;
            }

            $playList = $video->playList;
            if($playList != null) {
                $playList->videoList = $playList->videos;
                foreach ($playList->videoList as $item)
                    $item = getVideoFullInfo($item, false);
            }
            $localStorageData = ['title' => $video->title, 'pic' => $video->pic , 'redirect' => route('video.show', ['code' => $video->code])];

            $hasPlace = VideoPlaceRelation::where('videoId', $video->id)->count();
            if($hasPlace > 0)
                $video->hasPlace = true;
            else
                $video->hasPlace = false;

            $video->bookMark = 0;
            if($uId != 0)
                $video->bookMark = VideoBookMark::where('userId', $uId)->where('videoId', $video->id)->count();

            $video->tags = [];
            $videoTagRel = VideoTagRelation::where('videoId', $video->id)->pluck('tagId')->toArray();
            if(count($videoTagRel) > 0)
                $video->tags = Tags::whereIn('id', $videoTagRel)->pluck('name')->toArray();

            return view('page.videoShow', compact(['video', 'userMoreVideo', 'sameCategory', 'localStorageData', 'isLink', 'playList']));
        }

        return redirect(route('index'));

    }

    public function setVideoFeedback(Request $request)
    {
        $user = \Auth::user();
        if (isset($request->kind) && isset($request->videoId)) {
            $video = Video::find($request->videoId);
            if ($video != null) {
                if ($request->kind == 'likeVideo') {
                    $feedback = VideoFeedback::where('videoId', $request->videoId)
                                                ->where('userId', $user->id)
                                                ->whereNotNull('like')
                                                ->first();
                    if ($feedback != null && $request->like == 0)
                        $feedback->delete();
                    elseif ($feedback == null) {
                        $feedback = new VideoFeedback();
                        $feedback->videoId = $request->videoId;
                        $feedback->userId = $user->id;
                        $feedback->like = $request->like;
                        $feedback->save();
                    }
                    else {
                        $feedback->like = $request->like;
                        $feedback->save();
                    }
                }
                else if ($request->kind == 'likeComment') {
                    $feedback = VideoFeedback::where('videoId', $request->videoId)
                        ->where('commentId', $request->commentId)
                        ->where('userId', $user->id)
                        ->whereNotNull('like')->first();
                    if ($feedback != null) {
                        if($feedback->like == $request->like)
                            $feedback->like = 0;
                        else
                            $feedback->like = $request->like;

                        $feedback->save();
                    }
                    else {
                        $feedback = new VideoFeedback();
                        $feedback->videoId = $request->videoId;
                        $feedback->commentId = $request->commentId;
                        $feedback->userId = $user->id;
                        $feedback->like = $request->like;
                        $feedback->save();
                    }

                    $likeCount = VideoFeedback::where('videoId', $request->videoId)->where('commentId', $request->commentId)->where('like', 1)->count();
                    $disLikeCount = VideoFeedback::where('videoId', $request->videoId)->where('commentId', $request->commentId)->where('like', -1)->count();

                    return response()->json(['status' => 'ok', 'like' => $likeCount, 'disLike' => $disLikeCount]);
                }
                else
                    return response()->json(['status' => 'nok2']);

                $fullInfo = getVideoFullInfo($video, false);
                return response()->json(['status' => 'ok', 'like' => $fullInfo->like, 'disLike' => $fullInfo->disLike, 'commentsCount' => $fullInfo->commentsCount]);
            }
            else
                return response()->json(['status' => 'nok1']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function setVideoComment(Request $request)
    {
        $videoId = json_decode($request->data)->videoId;
        $video = Video::find($videoId);
        if($video != null){
            $newComment = new VideoComment();
            $newComment->videoId = $video->id;
            $newComment->parent = $request->ansTo;
            $newComment->text = $request->text;
            $newComment->userId = auth()->user()->id;
            $newComment->save();

            if($request->ansTo != 0){
                $parent = VideoComment::find($request->ansTo);
                $parent->haveAns = 1;
                $parent->save();
            }

            $comment = getVideoCommentInfos($newComment);

            echo json_encode(['status' => 'ok', 'comment' => $comment]);
        }
        else
            echo json_encode(['status' => 'nok', 'msg' => 'not find video']);

        return;
    }

    public function storeSeenLog(Request $request)
    {
        if(isset($request->seenPageLogId) && $request->seenPageLogId != 0){
            $log = UserSeenLog::find($request->seenPageLogId);
            if(auth()->check())
                $log->userId = auth()->user()->id;
            $log->seenTime = $log->seenTime + 5;
            $log->save();
            return response()->json(['status' => 'ok', 'seenPageLogId' => $log->id]);
        }

        $nowDate = Carbon::now()->format('Y-m-d');
        if(!isset($_COOKIE['userCodeTV'])){
            $userCode = generateRandomString(10);
            while(UserSeenLog::where('userCode', $userCode)->count() > 0)
                $userCode = generateRandomString(10);

            setcookie('userCodeTV', $userCode, time()+(86400*30));
        }
        else
            $userCode = $_COOKIE['userCodeTV'];

        if(auth()->check())
            $seenLog = UserSeenLog::create([
                'userId' => auth()->user()->id,
                'userCode' => $userCode,
                'url' => $request->url,
                'seenTime' => 0,
                'date' => $nowDate,
                'isMobile' => $request->isMobile,
                'width' => $request->width,
                'height' => $request->height,
                'relatedId' => 0,
            ]);
        else
            $seenLog = UserSeenLog::create([
                'userCode' => $userCode,
                'url' => $request->url,
                'seenTime' => 0,
                'date' => $nowDate,
                'isMobile' => $request->isMobile,
                'width' => $request->width,
                'height' => $request->height,
                'relatedId' => 0,
            ]);

        return response()->json(['status' => 'ok', 'seenPageLogId' => $seenLog->id]);
    }


    public function registerInCarpetMatch()
    {
        if(\auth()->check()){
            $user = \auth()->user();
            $userInEvent = UserEventRegistr::where('userId', $user->id)->where('event', 'carpet')->first();

//            if($userInEvent == null){
//                $lives = Live::where('isLive', 1)->orderBy('sDate')->orderBy('sTime')->first();
//                if($lives != null)
//                    return redirect(route('streaming.live', ['room' => $lives->code]));
//                else
//                    return redirect(url('/'));
//            }
//            else
//                return redirect(url('/'))->with(['msg' => 'notRegisterInCarpet']);


            if($userInEvent == null){
                $userInEvent = new UserEventRegistr();
                $userInEvent->userId = $user->id;
                $userInEvent->event = 'carpet';
                $userInEvent->save();

                return redirect()->back()->with(['msg' => 'carpetRegister']);
            }
            else
                return redirect()->back()->with(['msg' => 'youHasIn']);
        }
        else
            return redirect(url('/'))->with(['needToLogin' => 1]);
    }

}
