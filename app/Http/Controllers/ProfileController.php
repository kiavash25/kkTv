<?php

namespace App\Http\Controllers;

use App\models\User;
use App\models\UserPlayList;
use App\models\UserTvInfo;
use App\models\UserVideoCategory;
use App\models\Video;
use App\models\VideoBookMark;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile(User $user)
    {
        $yourPage = 0;
        if(auth()->check() && auth()->user()->id == $user->id)
            $yourPage = 1;

        if(isset($user->tvInfos->bannerPic))
            $user->bannerPic = \URL::asset('userProfile/'.$user->tvInfos->bannerPic);
        else
            $user->bannerPic = \URL::asset('images/mainPics/defaultBanner.jpg');

        $confirmContidition = ['state' => 1, 'confirm' => 1, 'userId' => $user->id];
        $lastVideos = Video::where($confirmContidition)
                        ->take(10)
                        ->orderByDesc('created_at')
                        ->get();

        foreach ($lastVideos as $lvid)
            $lvid = getVideoFullInfo($lvid, false);

        $loc = __DIR__.'/../../../../assets/_images/video/' . $user->id;

        $playListsVideos = [];
        $allPlayList = [];
        $playLists = UserPlayList::where('userId', $user->id)->get();
        foreach ($playLists as $item){
            $lVideo = $item->videos()->first();

            $item->videoCount = $item->videos()->count();
            $item->videos;

            if($lVideo != null) {
                $hasMain = is_file($loc . '/min_' . $lVideo->thumbnail) ? 'min_' : '';
                $item->thumbnail = \URL::asset('videos/' . $lVideo->userId . '/' . $hasMain . $lVideo->thumbnail);
                $item->url = route('video.show', ['code' => $lVideo->code]);
                array_push($playListsVideos, $item);
            }

            if($yourPage == 1)
                array_push($allPlayList, $item);
        }

        $allVideos = Video::where($confirmContidition)
                            ->orderByDesc('created_at')
                            ->get();

        foreach ($allVideos as $alVid)
            $alVid = getVideoFullInfo($alVid, false);

        $userCategories = UserVideoCategory::where('userId', $user->id)->get();
        foreach($userCategories as $item){
            $item->vid = $item->videos()->orderByDesc('created_at')->get();
            foreach ($item->vid as $vid)
                $vid = getVideoFullInfo($vid, false);
        }

        $bookMarked = $user->bookMarkVideos;
        foreach ($bookMarked as $item)
            $item = getVideoFullInfo($item, false);

        $topVideo = Video::where($confirmContidition)
                        ->where('isTopVideo', 1)->first();
        if($topVideo != null)
            $topVideo = getVideoFullInfo($topVideo, false);

        $user->pic = getUserPic($user->id);
        return view('page.profile.mainProfile',  compact(['user', 'lastVideos', 'playListsVideos', 'allPlayList', 'allVideos', 'userCategories', 'yourPage', 'bookMarked', 'topVideo']));
    }

    public function addToBookMark(Request $request)
    {
        if(isset($request->id)){
            $video = Video::find($request->id);
            if($video != null){
                $user = auth()->user();
                $bookMark = VideoBookMark::where('userId', $user->id)
                                            ->where('videoId', $video->id)->first();
                if($bookMark == null){
                    VideoBookMark::create([
                        'userId' => $user->id,
                        'videoId' => $video->id
                    ]);
                    return response()->json(['status' => 'create']);
                }
                else{
                    $bookMark->delete();
                    return response()->json(['status' => 'delete']);
                }
            }
            else
                return response()->json(['status' => 'notFound']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function updateTopVideo(Request $request)
    {
        if(isset($request->id)){
            $uId = auth()->user()->id;
            $video = Video::find($request->id);
            if($video != null && $video->userId == $uId){
                Video::where('userId', $uId)->update(['isTopVideo' => null]);
                $video->isTopVideo = 1;
                $video->save();

                return response()->json(['status' => 'ok']);
            }
            else
                return response()->json(['status' => 'notFound']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function updateBannerPic(Request $request)
    {

        if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
            if($_FILES['file']['size'] < 2000000) {
                $userInfo = UserTvInfo::where('userId', auth()->user()->id)->first();
                if($userInfo == null){
                    $userInfo = new UserTvInfo();
                    $userInfo->userId = auth()->user()->id;
                    $userInfo->save();
                }

                $type = explode('.', $_FILES['file']['name']);
                $fileName = time().rand(100, 999).'.'.end($type);
                $location = __DIR__ . '/../../../../assets/userProfile/';
                $result = move_uploaded_file($_FILES['file']['tmp_name'], $location.$fileName);
                if ($result){
                    if($userInfo->bannerPic != null && is_file($location.$userInfo->bannerPic))
                        unlink($location.$userInfo->bannerPic);
                    $userInfo->bannerPic = $fileName;
                    $userInfo->save();

                    return response()->json(['status' => 'ok']);
                }
                else
                    return response()->json(['status' => 'err1']);
            }
            else
                return response()->json(['status' => 'bigFile']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

}
