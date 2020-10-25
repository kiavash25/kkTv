<?php

namespace App\Http\Controllers;

use App\models\UserSeenLog;
use App\models\Video;
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
        $confirmContidition = ['state' => 1, 'confirm' => 1];
        $lastVideos = Video::where($confirmContidition)
                            ->take(10)
                            ->orderByDesc('created_at')
                            ->get();

        foreach ($lastVideos as $lvid)
            $lvid = $this->getVideoFullInfo($lvid, false);

        $videoCategory = VideoCategory::where('parent', 0)->get();
        foreach ($videoCategory as $vic) {
            $catId = VideoCategory::where('parent', $vic->id)->pluck('id')->toArray();
            $vic->video = Video::where($confirmContidition)->whereIn('categoryId', $catId)->take(10)->orderByDesc('created_at')->get();
            foreach ($vic->video as $catVid)
                $catVid = $this->getVideoFullInfo($catVid, false);
        }

        $topId = [];
        $topVideosId = \DB::select('SELECT videoId, COUNT(id) as likeCount FROM videoFeedbacks WHERE commentId IS NULL AND `like` = 1 GROUP BY videoId ORDER BY likeCount DESC LIMIT 10');
        foreach ($topVideosId as $item)
            array_push($topId, $item->videoId);

        $topVideos = Video::whereIn('id', $topId)->get();
        foreach ($topVideos as $item)
            $item = $this->getVideoFullInfo($item, false);

        return view('mainPage', compact(['lastVideos', 'videoCategory', 'topVideos']));
    }

    public function videoList($kind, $value){
        if($kind == 'category'){
            $category = VideoCategory::find($value);
            if($category == null)
                return redirect(route('streaming.index'));
            else{
                if($category->parent == 0) {
                    $confirmContidition = ['state' => 1, 'confirm' => 1];

                    $category->subs = VideoCategory::where('parent', $category->id)->get();
                    foreach ($category->subs as $item)
                        $item->icon = \URL::asset('images/video/category/'.$item->offIcon);

                    $subsId = VideoCategory::where('parent', $category->id)->pluck('id')->toArray();
                    $category->lastVideo = Video::where($confirmContidition)->whereIn('categoryId', $subsId)->take(10)->orderByDesc('created_at')->get();
                    foreach ($category->lastVideo  as $catVid)
                        $catVid = $this->getVideoFullInfo($catVid, false);

                    foreach ($category->subs as $item){
                        $item->video = Video::where($confirmContidition)->where('categoryId', $item->id)->take(10)->orderByDesc('created_at')->get();
                        $item->totalCount = Video::where($confirmContidition)->where('categoryId', $item->id)->count();
                        foreach ($item->video as $catVid)
                            $catVid = $this->getVideoFullInfo($catVid, false);
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

        $confirmContidition = ['state' => 1, 'confirm' => 1];

        if($kind == 'category'){
            $category = VideoCategory::find($value);

            if($category != null){
                if($category->parent == 0)
                    $catId = VideoCategory::where('parent', $category->id)->pluck('id')->toArray();
                else
                    $catId = [$category->id];

                $videos = Video::where($confirmContidition)->whereIn('categoryId', $catId)->skip(($page - 1) * $perPage)->take($perPage)->orderByDesc('created_at')->get();
                foreach ($videos as $item)
                    $item = $this->getVideoFullInfo($item, false);

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
            $video = $this->getVideoFullInfo($video, true);

            $userMoreVideo = Video::where('userId', $video->userId)->where('id', '!=', $video->id)->take(4)->orderByDesc('created_at')->get();
            $sameCategory = Video::where('categoryId', $video->categoryId)->where('id', '!=', $video->id)->take(7)->orderByDesc('created_at')->get();

            foreach ([$userMoreVideo, $sameCategory] as $categ)
                foreach ($categ as $vid)
                    $vid = $this->getVideoFullInfo($vid, false);

            if($video->userId == $uId && $video->link == null) {
                $video->link = URL::asset('videos/' . $video->userId . '/' . $video->video);
                $isLink = false;
            }

            $localStorageData = ['title' => $video->title, 'pic' => $video->pic , 'redirect' => route('video.show', ['code' => $video->code])];

            return view('page.videoShow', compact(['video', 'userMoreVideo', 'sameCategory', 'localStorageData', 'isLink']));
        }

        return redirect(route('index'));

    }

    public function uploadVideoPage()
    {
        $this->deleteLimbo();

        $categories = VideoCategory::where('parent', 0)->get();
        foreach ($categories as $item)
            $item->sub = VideoCategory::where('parent', $item->id)->get();

        while (true) {
            $code = random_int(10000, 99999);
            $check = VideoLimbo::where('code', $code)->first();
            if ($check == null) {
                $newLimbo = new VideoLimbo();
                $newLimbo->code = $code;
                $newLimbo->userId = auth()->user()->id;
                $newLimbo->save();
                break;
            }
        }

        return view('page.videoUpload', compact(['categories', 'code']));
    }

    private function deleteLimbo()
    {
        $limbos = VideoLimbo::all();
        foreach ($limbos as $item) {
            $diff = Carbon::now()->diffInHours($item->created_at);
            if ($diff > 3) {
                $location = __DIR__ . '/../../../../assets/_images/video/limbo/' . $item->video;
                if (is_file($location))
                    unlink($location);
                $item->delete();
            }
        }

        return;
    }

    public function storeVideo(Request $request)
    {
        $user = auth()->user();
        if (isset($_FILES['video']) && isset($request->code) && $_FILES['video']['error'] == 0) {
            $limbo = VideoLimbo::where('code', $request->code)->where('userId', $user->id)->first();
            if ($limbo != null) {
                $videoName = time() . $_FILES['video']['name'];
                $location = __DIR__ . '/../../../../assets/_images/video';
//                $location ='videos';
                if (!is_dir($location))
                    mkdir($location);

                $location .= '/limbo';
                if (!is_dir($location))
                    mkdir($location);
                $location .= '/' . $videoName;

                if (move_uploaded_file($_FILES['video']['tmp_name'], $location)) {
                    $limbo->video = $videoName;
                    $limbo->save();

                    try {
                        $ffprobe = \FFMpeg\FFProbe::create();
                        $duration = (int)$ffprobe->format($location)->get('duration');
                        $second = $duration % 60;
                        $duration = (int)($duration / 60);
                        $min = $duration % 60;
                        $duration = (int)($duration / 60);

                        if ($second < 10)
                            $second = '0' . $second;
                        if ($min < 10)
                            $min = '0' . $min;
                        $duration = $duration . ':' . $min . ':' . $second;
                    } catch (Throwable $e) {
                        $duration = '01:00:00';
                    }


                    echo json_encode(['status' => 'ok', 'duration' => $duration]);
                } else
                    echo json_encode(['status' => 'nok2']);
            } else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeVideoInfo(Request $request)
    {
        $user = auth()->user();
        if (isset($request->name) && isset($request->code) && isset($request->categoryId)) {

            $limbo = VideoLimbo::where('code', $request->code)->where('userId', $user->id)->first();
            if ($limbo != null) {
                $location = __DIR__ . '/../../../../assets/_images/video';
                $nLoc = $location . '/' . $user->id;
                if (!is_dir($nLoc))
                    mkdir($nLoc);

                $img = $_POST['thumbnail'];
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $thumbanil = time() . rand(100, 999) . '.jpg';

                $file = $nLoc . '/' . $thumbanil;
                $success = file_put_contents($file, $data);

                $img = \Image::make($file);
                $img->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($nLoc . '/min_' . $thumbanil);

                while (true) {
                    $sCode = generateRandomString(10);
                    $check = Video::where('code', $sCode)->first();
                    if ($check == null)
                        break;
                }

                $newVideo = new Video();
                $newVideo->userId = $user->id;
                $newVideo->code = $sCode;
                $newVideo->title = $request->name;
                $newVideo->description = $request->description;
                $newVideo->video = $limbo->video;
                $newVideo->categoryId = $request->categoryId;
                $newVideo->duration = $request->duration;
                $newVideo->thumbnail = $thumbanil;
                $newVideo->seen = 0;
                $newVideo->confirm = 1;
                $newVideo->state = $request->state;
                $newVideo->save();

                $limboLoc = $location . '/limbo/' . $limbo->video;
                $nLoc .= '/' . $limbo->video;
                rename($limboLoc, $nLoc);

                if (isset($request->places) && $request->places != null) {
                    $places = explode(',', $request->places);
                    foreach ($places as $place) {
                        $p = explode('_', $place);
                        $check = VideoPlaceRelation::where('videoId', $newVideo->id)
                            ->where('kindPlaceId', $p[0])
                            ->where('placeId', $p[1])
                            ->first();
                        if ($check == null) {
                            $newRel = new VideoPlaceRelation();
                            $newRel->videoId = $newVideo->id;
                            $newRel->kindPlaceId = (int)$p[0];
                            $newRel->placeId = (int)$p[1];
                            $newRel->save();
                        }
                    }
                }

                if (isset($request->tags) && $request->tags != null) {
                    $tags = explode(',', $request->tags);
                    foreach ($tags as $tag) {
                        $t = explode('_', $tag);
                        if ($t[0] == 'old')
                            $tagId = $t[1];
                        else
                            $tagId = storeNewTag($t[1]);

                        $newTagRel = new VideoTagRelation();
                        $newTagRel->videoId = $newVideo->id;
                        $newTagRel->tagId = $tagId;
                        $newTagRel->save();
                    }
                }

                $url = route('video.show', ['code' => $newVideo->code]);

                echo json_encode(['status' => 'ok', 'url' => $url]);
            } else
                echo json_encode(['status' => 'nok1']);
        } else
            echo json_encode(['status' => 'nok']);

        return;
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

                $fullInfo = $this->getVideoFullInfo($video, false);
                return response()->json(['status' => 'ok', 'like' => $fullInfo->like, 'disLike' => $fullInfo->disLike, 'commentsCount' => $fullInfo->commentsCount]);
            }
            else
                return response()->json(['status' => 'nok1']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    private function getVideoFullInfo($video, $main = false)
    {
        $userLogin = auth()->check();

        $loc = __DIR__.'/../../../../assets/_images/video/' . $video->userId;
        if(is_file($loc .'/min_'. $video->thumbnail))
            $video->pic = \URL::asset('videos/' . $video->userId . '/min_' . $video->thumbnail);
        else
            $video->pic = \URL::asset('videos/' . $video->userId . '/' . $video->thumbnail);

        $video->url = route('video.show', ['code' => $video->code]);
        $video->username = User::find($video->userId)->username;
        $video->userPic = getUserPic($video->userId);
        $video->time = getDifferenceTimeString($video->created_at);

        $video->like = VideoFeedback::where('videoId', $video->id)->whereNull('commentId')->where('like', 1)->count();
        $video->disLike = VideoFeedback::where('videoId', $video->id)->whereNull('commentId')->where('like', -1)->count();
        $video->commentsCount = VideoComment::where('videoId', $video->id)->count();
        $video->comments = [];
        $video->places = [];

        if($main){
            $video->pic = \URL::asset('videos/' . $video->userId . '/' . $video->thumbnail);
            $resultComment = [];
            $video->comments = $this->getVideoComments($video->id, 0);

            $video->uLike = 0;
            if($userLogin){
                $uLike = VideoFeedback::where('videoId', $video->id)->where('userId', auth()->user()->id)->first();
                if($uLike != null)
                    $video->uLike = $uLike->like;
            }
        }

        return $video;
    }

    private function getVideoComments($videoId, $parent){
        $comments = VideoComment::where('videoId', $videoId)
            ->where(function ($query) {
                $uId = 0;
                if(auth()->check())
                    $uId = auth()->user()->id;

                $query->where('confirm', 1)
                    ->orWhere('userId', $uId);
            })
            ->where('parent', $parent)
            ->orderBy('created_at')->get();

        foreach ($comments as $comment)
            $comment = $this->getVideoCommentInfos($comment);

        return $comments;
    }

    private function getVideoCommentInfos($_comment){
        $comment = $_comment;
        $ucomment = User::find($comment->userId);
        if ($ucomment != null) {
            $comment->username = $ucomment->username;
            $comment->userPic = getUserPic($ucomment->id);
            $comment->like = VideoFeedback::where('videoId', $comment->videoId)->where('commentId', $comment->id)->where('like', 1)->count();
            $comment->disLike = VideoFeedback::where('videoId', $comment->videoId)->where('commentId', $comment->id)->where('like', -1)->count();
            $comment->time = getDifferenceTimeString($comment->created_at);

            if($comment->haveAns == 1)
                $comment->comments = $this->getVideoComments($comment->videoId, $comment->id);
            else
                $comment->comments = [];

            if($comment->parent != 0)
                $comment->ansToUsername = User::find(VideoComment::find($comment->parent)->userId)->username;
            $comment->ansCount = count($comment->comments);
        }

        return $comment;
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

            $comment = $this->getVideoCommentInfos($newComment);

            echo json_encode(['status' => 'ok', 'comment' => $comment]);
        }
        else
            echo json_encode(['status' => 'nok', 'msg' => 'not find video']);

        return;
    }

    public function streamingLive($room = '')
    {
        $data = [
            'title' => '',
            'desc' => '',
            'user' => '',
            'chats' => [],
            'uniqueUser' => 0,
            'userPic' => getUserPic(0),
            'like' => 0,
            'disLike' => 0,
            'haveVideo' => false
        ];
        $hasVideo = false;

        if($room != null){
            $video = Live::where('code', $room)->where('isLive', 1)->first();
            $today = Carbon::now()->format('Y-m-d');
            $nowTime = Carbon::now()->format('H:i');
            if($video != null){
                $user = User::find($video->userId);
                $user->pic = getUserPic($user->id);
                $video->user = $user;
                $hasVideo = true;

                $video->likeCount = LiveFeedBack::where('videoId', $video->id)->where('like', 1)->count();
                $video->disLikeCount = LiveFeedBack::where('videoId', $video->id)->where('like', -1)->count();

                $video->chats = LiveChat::where('roomId', $room)->select(['id', 'text', 'username', 'userPic'])->get();
                $video->uniqueUser = LiveChat::where('roomId', $room)->get()->groupBy('userId')->count();

                $lastChatId = LiveChat::where('roomId', $room)->orderByDesc('id')->first();
                if($lastChatId == null)
                    $lastChatId = 0;
                else
                    $lastChatId = $lastChatId->id;

                $video->guest = LiveGuest::where('videoId', $video->id)->get();
                foreach ($video->guest as $guest)
                    $guest->pic = URL::asset('_images/video/live/'.$guest->videoId.'/'.$guest->pic);

                $video->youLike = 0;
                if(auth()->check()){
                    $yl = LiveFeedBack::where('videoId', $video->id)->where('userId', auth()->user()->id)->first();
                    if($yl != null)
                        $video->youLike = $yl->like;
                }

            }
            else
                $room = '';

            $user = null;
            if(auth()->check()){
                $user = User::select(['id', 'username'])->find(\auth()->user()->id);
                $user->pic = getUserPic(auth()->user()->id);
            }
        }


        return view('streamingLive', compact(['room', 'video', 'hasVideo', 'user', 'lastChatId']));
    }

    public function storeSeenLog(Request $request)
    {
        $nowDate = '1399-08-04';
        if(!isset($_COOKIE['userCodeTV'])){
            $userCode = generateRandomString(10);
            while(UserSeenLog::where('userCode', $userCode)->count() > 0)
                $userCode = generateRandomString(10);

            setcookie('userCodeTV', $userCode, time()+(86400*30));
        }
        else
            $userCode = $_COOKIE['userCodeTV'];

        $url = $request->url;
        $seenLog = UserSeenLog::where('url', $url)->where('userCode', $userCode)->first();
        if($seenLog == null){
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
        }
        else{
            $seenLog->seenTime = $seenLog->seenTime+5;
            $seenLog->save();
        }

        if(\auth()->check() && $seenLog->userId == null){
            $seenLog->userId = \auth()->user()->id;
            $seenLog->save();
        }


        return response()->json(['status' => 'ok', 'seenPageLogId' => $seenLog->id]);
    }

    public function storeLiveChat(Request $request)
    {
        if(\auth()->check()){
            $chat = new LiveChat();
            $chat->text = $request->text;
            $chat->userId = \auth()->user()->id;
            $chat->roomId = $request->room;
            $chat->username = \auth()->user()->username;
            $chat->userPic = $request->userPic;
            $chat->save();

            return response()->json(['status' => 'ok']);
        }
        else
            return response()->json(['status' => 'notAuth']);
    }

    public function updateLiveVideoChat($room)
    {
        $chats = LiveChat::where('roomId', $room)->where('id', '>', $_GET['lastId'])->select(['id', 'text', 'username', 'userPic'])->get();
        $lastChatId = LiveChat::where('roomId', $room)->orderByDesc('id')->first();
        if($lastChatId == null)
            $lastChatId = 0;
        else
            $lastChatId = $lastChatId->id;

        return response()->json(['status' => 'ok', 'chats' => $chats, 'lastChatId' => $lastChatId]);
    }

    public function sendBroadcastMsg(Request $request)
    {
        if(\auth()->check()) {
            broadcast(new CommentBroadCast($request->msg, $request->room, $request->userName, $request->userPic));
            $live = Live::where('code', $request->room)->first();

            $chat = new LiveChat();
            $chat->videoId = $live->id;
            $chat->userId = \auth()->user()->id;
            $chat->text = $request->msg;
            $chat->username = $request->userName;
            $chat->userPic = $request->userPic;
            $chat->save();

            $count = LiveChat::where('videoId', $live->id)->count();
            $uniqueUser = LiveChat::where('videoId', $live->id)->groupBy('userId')->get();
            $uniqueUser = count($uniqueUser);
            echo json_encode(['count' => $count, 'uniqueUser' => $uniqueUser]);
        }
    }

    public function setLiveFeedback(Request $request)
    {
        if(isset($request->room) && isset($request->like)){
            $video = Live::where('code', $request->room)->first();
            if($video != null){
                $like = LiveFeedBack::where('videoId', $video->id)->where('userId', auth()->user()->id)->first();
                if($like == null){
                    $like = new LiveFeedBack();
                    $like->videoId = $video->id;
                    $like->userId = auth()->user()->id;
                }

                if($request->like == $like->like)
                    $like->like = 0;
                else
                    $like->like = $request->like;

                $like->save();

                $likeCount = LiveFeedBack::where('videoId', $video->id)->where('like', 1)->count();
                $disLikeCount = LiveFeedBack::where('videoId', $video->id)->where('like', -1)->count();

                return response()->json(['status' => 'ok', 'like' => $likeCount, 'disLike' => $disLikeCount, 'youLike' => $like->like]);
            }
            else
                return response()->json(['status' => 'nok1']);
        }
        else
            return response()->json(['status' => 'nok']);
    }
}
