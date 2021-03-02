<?php

namespace App\Http\Controllers;

use App\models\Live;
use App\models\LiveChat;
use App\models\LiveFeedBack;
use App\models\LiveGuest;
use App\models\UserEventRegistr;
use App\models\UserSeenLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class LiveController extends Controller
{
    public function streamingLive($room = '')
    {
        if(!auth()->check())
            return redirect(url('/'))->with(['needToLogin' => 1]);
        else{
            $user = auth()->user();
            $checkRegisterInCarpet = UserEventRegistr::where('userId', $user->id)->where('event', 'carpet')->first();
            if($checkRegisterInCarpet == null)
                return redirect(url('/'))->with(['msg' => 'notRegisterInCarpet']);
        }


        $lastChatId = 0;
        $startVideo = -1;
        $videoUrl = '';
        $chats = [];

        if($room != null){
            $video = Live::where('code', $room)->first();
            date_default_timezone_set('Asia/Tehran');
            $today = Carbon::now()->format('Y-m-d');
            $nowTime = Carbon::now()->format('H:i');

            if($video != null && $video->isLive == 1 && $video->sDate == $today){
                $startVideo = $nowTime >= $video->sTime ? 1 : $video->sTime.':00';
                $video->date = Carbon::createFromFormat('Y-m-d', $video->sDate)->toFormattedDateString();
                $video->banner = URL::asset("images/liveBanners/{$video->beforeBanner}");

                $user = User::find($video->userId);
                $user->pic = getUserPic($user->id);
                $video->user = $user;

                $video->likeCount = LiveFeedBack::where('videoId', $video->id)->where('like', 1)->count();
                $video->disLikeCount = LiveFeedBack::where('videoId', $video->id)->where('like', -1)->count();

                if($video->haveChat == 1) {
                    $chats = LiveChat::where('roomId', $room)->select(['id', 'text', 'username', 'userPic'])->get();
                    $video->uniqueUser = LiveChat::where('roomId', $room)->get()->groupBy('userId')->count();
                    $lastChatId = LiveChat::where('roomId', $room)->orderByDesc('id')->first();
                    $lastChatId = $lastChatId == null ? 0 : $lastChatId->id;
                }

                $video->guest = LiveGuest::where('videoId', $video->id)->get();
                foreach ($video->guest as $guest)
                    $guest->pic = URL::asset('_images/video/live/'.$guest->videoId.'/'.$guest->pic);

                $user = null;
                $video->youLike = 0;
                if(auth()->check()){
                    $yl = LiveFeedBack::where('videoId', $video->id)->where('userId', auth()->user()->id)->first();
                    if($yl != null)
                        $video->youLike = $yl->like;

                    $user = User::select(['id', 'username'])->find(\auth()->user()->id);
                    $user->pic = getUserPic(auth()->user()->id);
                }

                return view('streamingLive', compact(['room', 'video', 'chats', 'user', 'lastChatId', 'startVideo']));
            }
        }

        return redirect(route('index'));
    }

    public function getLiveUrl()
    {
        $code = $_GET['room'];
        $live = Live::where('code', $code)->first();
        if($live != null){

            if(isset($_GET['thisIsTest']) && $_GET['thisIsTest'] == 1)
                return response()->json(['status' => 'ok', 'url' => $live->url]);

            if($live->isLive == 1){
                date_default_timezone_set('Asia/Tehran');
                $today = Carbon::now()->format('Y-m-d');
                $nowTime = Carbon::now()->format('H:i');
                if($live->sDate == $today && $live->sTime <= $nowTime)
                    return response()->json(['status' => 'ok', 'url' => $live->url]);
                else
                    return response()->json(['status' => 'notTime']);
            }
            else
                return response()->json(['status' => 'error2']);
        }
        else
            return response()->json(['status' => 'error1']);
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

        $userSeen = 18333 + random_int(0, 200);

        return response()->json(['status' => 'ok', 'chats' => $chats, 'lastChatId' => $lastChatId, 'userSeen' => $userSeen]);
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

    function getLiveUserSeen(Request $request){
        $nowDate = '1399-09-30';
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
            $seenLog->seenTime = $seenLog->seenTime+30;
            $seenLog->save();
        }

        if(\auth()->check() && $seenLog->userId == null){
            $seenLog->userId = \auth()->user()->id;
            $seenLog->save();
        }

        $randomUser = random_int(300, 320);
        return response()->json(['status' => 'ok', 'seenPageLogId' => $seenLog->id, 'userSeenCount' => $randomUser]);
    }

    public function testLive($room = '', $playTime = '00:00')
    {
        $lastChatId = 0;
        $startVideo = -1;
        $videoUrl = '';
        $chats = [];

        $video = Live::where('code', $room)->first();
        date_default_timezone_set('Asia/Tehran');
        $today = Carbon::now()->format('Y-m-d');
        $nowTime = Carbon::now()->format('H:i');

        if($video != null){
            $startVideo = $nowTime >= $playTime ? 1 : $playTime.":00";
            $video->date = Carbon::createFromFormat('Y-m-d', $video->sDate)->toFormattedDateString();
            $video->banner = URL::asset('images/liveBanners/'.$video->beforeBanner);

            $user = User::find($video->userId);
            $user->pic = getUserPic($user->id);
            $video->user = $user;

            $video->likeCount = LiveFeedBack::where('videoId', $video->id)->where('like', 1)->count();
            $video->disLikeCount = LiveFeedBack::where('videoId', $video->id)->where('like', -1)->count();

            $video->guest = LiveGuest::where('videoId', $video->id)->get();
            foreach ($video->guest as $guest)
                $guest->pic = URL::asset('_images/video/live/'.$guest->videoId.'/'.$guest->pic);

            $user = null;
            $video->youLike = 0;
            if(auth()->check()){
                $yl = LiveFeedBack::where('videoId', $video->id)->where('userId', auth()->user()->id)->first();
                if($yl != null)
                    $video->youLike = $yl->like;

                $user = User::select(['id', 'username'])->find(\auth()->user()->id);
                $user->pic = getUserPic(auth()->user()->id);
            }

            $thisIsTest = true;
            return view('streamingLive', compact(['room', 'video', 'chats', 'user', 'lastChatId', 'startVideo', 'thisIsTest']));
        }
    }


}
