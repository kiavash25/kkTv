<?php

namespace App\Http\Controllers;

use App\models\User;
use App\models\UserPlayList;
use App\models\UserVideoCategory;
use App\models\Video;
use App\models\VideoBookMark;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile(User $user)
    {
        $confirmContidition = ['state' => 1, 'confirm' => 1, 'userId' => $user->id];
        $lastVideos = Video::where($confirmContidition)
                        ->take(10)
                        ->orderByDesc('created_at')
                        ->get();

        foreach ($lastVideos as $lvid)
            $lvid = getVideoFullInfo($lvid, false);

        $loc = __DIR__.'/../../../../assets/_images/video/' . $user->id;

        $playListsVideos = [];
        $playLists = UserPlayList::where('userId', $user->id)->get();
        foreach ($playLists as $item){
            $lVideo = $item->videos()->first();
            if($lVideo != null) {
                $hasMain = is_file($loc . '/min_' . $lVideo->thumbnail) ? 'min_' : '';
                $item->thumbnail = \URL::asset('videos/' . $lVideo->userId . '/' . $hasMain . $lVideo->thumbnail);
                $item->videoCount = $item->videos()->count();
                $item->url = route('video.show', ['code' => $lVideo->code]);
                array_push($playListsVideos, $item);
            }
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

        $yourPage = 0;
        if(auth()->check() && auth()->user()->id == $user->id)
            $yourPage = 1;

        $user->pic = getUserPic($user->id);
        return view('page.profile.mainProfile',  compact(['user', 'lastVideos', 'playListsVideos', 'allVideos', 'userCategories', 'yourPage', 'bookMarked']));
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
}
