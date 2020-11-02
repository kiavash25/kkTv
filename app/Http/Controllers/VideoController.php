<?php

namespace App\Http\Controllers;

use App\models\FestivalLimboContent;
use App\models\places\Cities;
use App\models\places\Place;
use App\models\places\State;
use App\models\Tags;
use App\models\UserPlayList;
use App\models\UserPlayListRelations;
use App\models\UserVideoCategory;
use App\models\UserVideoCategoryRelations;
use App\models\Video;
use App\models\VideoCategory;
use App\models\VideoCategoryRelation;
use App\models\VideoLimbo;
use App\models\VideoPlaceRelation;
use App\models\VideoTagRelation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function uploadVideoPage()
    {
        $this->deleteLimbo();

        $categories = VideoCategory::where('parent', 0)->get();
        foreach ($categories as $item)
            $item->sub = VideoCategory::where('parent', $item->id)->get();

        $userCategories = UserVideoCategory::where('userId', auth()->user()->id)->get();
        $userPlayList = UserPlayList::where('userId', auth()->user()->id)->get();

        return view('page.videoUpload', compact(['categories', 'userCategories', 'userPlayList']));
    }

    public function newYourCategory(Request $request)
    {
        if(isset($request->text)){
            $uvc = UserVideoCategory::where('userId', auth()->user()->id)
                                        ->where('name', $request->text)
                                        ->first();
            if($uvc == null){
                $uvc = new UserVideoCategory();
                $uvc->userId = auth()->user()->id;
                $uvc->name = $request->text;
                $uvc->save();

                return response()->json(['status' => 'ok', 'result' => $uvc]);
            }
            else
                return response()->json(['status' => 'duplicate']);
        }
        else
            return response()->json(['status' => 'nok']);
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

    public function storeVideoInfo(Request $request)
    {
        $user = auth()->user();
        if (isset($request->name) && isset($request->fileName) && isset($request->mainCategory)) {

            $limbo = VideoLimbo::where('video', $request->fileName)
                                ->where('userId', $user->id)
                                ->first();

            $categoryCheck = VideoCategory::find($request->mainCategory);
            if($categoryCheck == null)
                return response()->json(['status' => 'categoryNotFound']);

            if ($limbo != null ) {
                $location = __DIR__ . '/../../../../assets/_images/video';
                $nLoc = $location . '/' . $user->id;
                if (!is_dir($nLoc))
                    mkdir($nLoc);

                $thumbanil = time() . rand(100, 999) . '_' . $user->id . '.jpg';
                $file = $nLoc . '/' . $thumbanil;
                uploadLargeFile($file, $_POST['thumbnail']);

                $img = \Image::make($file);
                $img->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($nLoc . '/min_' . $thumbanil);

                $sCode = generateRandomString(10);
                while (Video::where('code', $sCode)->count() > 1)
                    $sCode = generateRandomString(10);

                $newVideo = new Video();
                $newVideo->userId = $user->id;
                $newVideo->code = $sCode;
                $newVideo->title = $request->name;
                $newVideo->description = $request->description;
                $newVideo->video = $request->fileName;
                $newVideo->categoryId = $request->mainCategory;
                $newVideo->duration = $request->duration;
                $newVideo->thumbnail = $thumbanil;
                $newVideo->seen = 0;
                $newVideo->confirm = 1;
                $newVideo->state = $request->state;
                $newVideo->save();

                if(isset($request->sideCategory)){
                    VideoCategoryRelation::create([
                        'videoId' => $newVideo->id,
                        'categoryId' => $request->mainCategory,
                        'isMain' => 1,
                    ]);
                    $sides = explode(',', $request->sideCategory);
                    foreach ($sides as $cat){
                        $sideCate = VideoCategory::find($cat);
                        if($sideCate != null)
                            VideoCategoryRelation::create([
                                'videoId' => $newVideo->id,
                                'categoryId' => $sideCate->id,
                                'isMain' => 0,
                            ]);
                    }
                }

                if(isset($request->userCategory) && $request->userCategory != 0){
                    $userCategory = UserVideoCategory::find($request->userCategory);
                    if($userCategory != null && $userCategory->userId == auth()->user()->id){
                        $newVideo->userCategoryId = $userCategory->id;
                        $newVideo->save();
                    }
                }

                if(isset($request->userPlayList) && $request->userPlayList != 0){
                    $userPlayList = UserPlayList::find($request->userPlayList);
                    if($userPlayList != null && $userPlayList->userId == auth()->user()->id){
                        $lastVideo = Video::where('playListId', $userPlayList->id)
                                            ->orderByDesc('playListRow')
                                            ->first();

                        $newVideo->playListId  = $userPlayList->id;
                        $newVideo->playListRow = $lastVideo == null ? 0 : $lastVideo->playListRow+1;
                        $newVideo->save();
                    }
                }

                if (isset($request->places) && $request->places != null) {
                    $places = explode(',', $request->places);
                    foreach ($places as $place) {
                        $p = explode('_', $place);
                        if($p[0] == 'state')
                            $checkExist = State::find($p[1]);
                        elseif($p[0] == 'city')
                            $checkExist = Cities::find($p[0]);
                        else{
                            $kindPlace = Place::find($p[0]);
                            if($kindPlace == null)
                                continue;
                            else
                                $checkExist = \DB::table($kindPlace->tableName)->find($p[1]);
                        }

                        if($checkExist == null)
                            continue;


                        $newRel = new VideoPlaceRelation();
                        $newRel->videoId = $newVideo->id;
                        if($p[0] == 'state' || $p[0] == 'city')
                            $newRel->kind = $p[0];
                        else
                            $newRel->kindPlaceId = (int)$p[0];
                        $newRel->placeId = (int)$p[1];
                        $newRel->save();
                    }
                }

                if (isset($request->tags) && $request->tags != null) {
                    $tags = explode(',', $request->tags);
                    foreach ($tags as $tag) {
                        $tTable = Tags::firstOrCreate(['name' => $tag]);
                        $newTagRel = new VideoTagRelation();
                        $newTagRel->videoId = $newVideo->id;
                        $newTagRel->tagId = $tTable->id;
                        $newTagRel->save();
                    }
                }

                $limboLoc = $location . '/limbo/' . $limbo->video;
                $nLoc .= '/' . $limbo->video;
                rename($limboLoc, $nLoc);

                $url = route('video.show', ['code' => $newVideo->code]);

                return response()->json(['status' => 'ok', 'url' => $url]);
            }
            else
                return response()->json(['status' => 'notYours']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function uploadVideoFile(Request $request)
    {
        $user = auth()->user();
        $data = json_decode($request->data);
        $direction = __DIR__.'/../../../../assets/_images/video';
        if(!is_dir($direction))
            mkdir($direction);

        $direction .= '/limbo';
        if(!is_dir($direction))
            mkdir($direction);

        if(isset($request->cancelUpload) && $request->cancelUpload == 1){
            $direction .= '/'.$request->storeFileName;
            if(is_file($direction))
                unlink($direction);
            VideoLimbo::where('userId', $user->id)
                        ->where('video', $request->storeFileName)
                        ->delete();
            return response()->json(['status' => 'canceled']);
        }

        if(isset($request->storeFileName) && isset($request->file_data) && $request->storeFileName != 0){
            $fileName = $request->storeFileName;
            $direction .= '/'.$fileName;
            $result = uploadLargeFile($direction, $request->file_data);
        }
        else if(isset($request->thumbnail) && $request->thumbnail != ''){
            $fileName = explode('.', $request->fileName);
            $fileName = $fileName[0].'.png';

            $direction .= '/'.$fileName;
            $result = uploadLargeFile($direction, $request->thumbnail);

            if($result) {
                $location = __DIR__ . '/../../../../assets/_images/festival/limbo';
                $size = [['width' => 250, 'height' => 250, 'name' => 'thumb_', 'destination' => $location]];
                $result = resizeUploadedImage(file_get_contents($direction), $size, $fileName);
                if(is_file($location.'/'.$fileName))
                    unlink($location.'/'.$fileName);

                $fileName = 'thumb_'.$fileName;

                $limbo = new FestivalLimboContent();
                $limbo->userId = $user->id;
                $limbo->content = $fileName;
                $limbo->save();
            }
        }
        else{
            $file_name = $request->file_name;
            $fileType = explode('.', $file_name);
            $fileName = time().'_'.$user->id.'.'.end($fileType);

            $direction .= '/'.$fileName;
            $result = uploadLargeFile($direction, $request->file_data);

            if($result) {
                $code = random_int(10000, 99999);
                while(VideoLimbo::where('code', $code)->count() > 0)
                    $code = random_int(10000, 99999);

                $newLimbo = new VideoLimbo();
                $newLimbo->code = $code;
                $newLimbo->userId = auth()->user()->id;
                $newLimbo->video = $fileName;
                $newLimbo->save();
            }
        }

//        if(isset($request->last) && $request->last == 'true' && $data->kind == 'photo'){
//            $location = __DIR__.'/../../../../assets/_images/festival/limbo';
//            $size = [[ 'width' => 250, 'height' => 250, 'name' => 'thumb_', 'destination' => $location ]];
//            $result = resizeUploadedImage(file_get_contents($direction), $size, $fileName);
//        }

        if($result)
            return response()->json(['status' => 'ok', 'fileName' => $fileName]);
        else
            return response()->json(['status' => 'nok']);
    }

    public function deleteUploadedFile(Request $request)
    {
        if(isset($request->fileName)){
            $file = VideoLimbo::where('video', $request->fileName)
                                ->where('userId', auth()->user()->id)
                                ->first();
            if($file != null){
                $direction = __DIR__.'/../../../../assets/_images/video/limbo/';
                if(is_file($direction.$request->fileName)){
                    unlink($direction.$request->fileName);
                    $file->delete();
                    return response()->json(['status' => 'ok']);
                }
                else
                    return response()->json(['status' => 'notFoundFile']);
            }
            else
                return response()->json(['status' => 'notFound']);
        }
        else
            return response()->json(['status' => 'nok']);
    }
}
