<?php

namespace App\Http\Controllers;

use App\models\UserPlayList;
use App\models\Video;
use Illuminate\Http\Request;

class PlayListController extends Controller
{
    public function newPlayList(Request $request)
    {
        if(isset($request->text)){
            $upl = UserPlayList::where('userId', auth()->user()->id)
                ->where('name', $request->text)
                ->first();
            if($upl == null){
                $upl = new UserPlayList();
                $upl->userId = auth()->user()->id;
                $upl->name = $request->text;
                $upl->save();

                $upl->videos;

                return response()->json(['status' => 'ok', 'result' => $upl]);
            }
            else
                return response()->json(['status' => 'duplicate']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function editPlayListName(Request $request)
    {
        if(isset($request->id) && isset($request->text)){
            $playList = UserPlayList::find($request->id);
            if($playList != null && $playList->userId == auth()->user()->id){
                $checkName = UserPlayList::where('name', $request->text)
                    ->where('userId', auth()->user()->id)
                    ->where('id', '!=', $request->id)
                    ->first();
                if($checkName == null){
                    $playList->name = $request->text;
                    $playList->save();

                    return response()->json(['status' => 'ok']);
                }
                else
                    return response()->json(['status' => 'duplicate']);
            }
            else
                return response()->json(['status' => 'notFound']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function editPlayListVideoSort(Request $request)
    {
        if(isset($request->id) && isset($request->newSort)){
            $playList = UserPlayList::find($request->id);
            if($playList != null && $playList->userId){
                $videos = Video::where('playListId', $playList->id)->get();
                foreach ($videos as $vid){
                    $index = array_search($vid->id, $request->newSort);
                    $vid->playListRow = $index+1;
                    $vid->save();
                }
                return response()->json(['status' => 'ok']);
            }
            else
                return response()->json(['status' => 'notFound']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function updatePlayListVideos(Request $request)
    {
        if(isset($request->playListId) && isset($request->videosId)){
            $playList = UserPlayList::find($request->playListId);
            if($playList != null && $playList->userId == auth()->user()->id){
                $videosId = [0];
                if(count($request->videosId) > 0)
                    $videosId = $request->videosId;

                Video::where('userId', auth()->user()->id)
                    ->where('playListId', $playList->id)
                    ->whereNotIn('id', $videosId)
                    ->update(['playListId' => null, 'playListRow' => 0]);

                $topNumber = 0;
                $lastPV = Video::where('playListId', $playList->id)->orderByDesc('playListRow')->first();
                if($lastPV != null)
                    $topNumber = $lastPV->playListRow;

                $selectedVideos = Video::whereIn('id', $videosId)->get();
                foreach ($selectedVideos as $vid){
                    $vid->playListId = $playList->id;
                    if($vid->playListRow == 0){
                        $vid->playListRow = $topNumber+1;
                        $topNumber++;
                    }
                    $vid->save();
                }

                $playLists = UserPlayList::where('userId', auth()->user()->id)->get();
                foreach ($playLists as $item){
                    $item->videoCount = $item->videos()->count();
                    $item->videos;
                }

                return response()->json(['status' => 'ok', 'result' => $playLists]);
            }
            else
                return response()->json(['status' => 'notFound']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function deleteVideoFromPlayList(Request $request)
    {
        if(isset($request->id)){
            $video = Video::find($request->id);
            if($video != null && $video->userId == auth()->user()->id){
                $video->playListRow = 0;
                $video->playListId = null;
                $video->save();

                return response()->json(['status' => 'ok']);
            }
            else
                return response()->json(['status' => 'notFound']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function deletePlayList(Request $request)
    {
        if(isset($request->id)){
            $playList = UserPlayList::find($request->id);
            if($playList != null && $playList->userId == auth()->user()->id){
                Video::where('playListId', $playList->id)
                        ->update([
                            'playListId' => null,
                            'playListRow' => 0,
                        ]);
                $playList->delete();

                $playLists = UserPlayList::where('userId', auth()->user()->id)->get();
                foreach ($playLists as $item){
                    $item->videoCount = $item->videos()->count();
                    $item->videos;
                }
                return response()->json(['status' => 'ok', 'result' => $playLists]);
            }
            else
                return response()->json(['status' => 'notFound']);
        }
        else
            return response()->json(['status' => 'nok']);

    }
}
