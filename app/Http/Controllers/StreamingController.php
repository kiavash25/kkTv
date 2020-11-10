<?php

namespace App\Http\Controllers;

use App\Events\CommentBroadCast;
use App\Events\UserOnline;
use App\models\Activity;
use App\models\Amaken;
use App\models\Cities;
use App\models\CityPic;
use App\models\Place;
use App\models\State;
use App\models\Video;
use App\models\VideoCategory;
use App\models\VideoComment;
use App\models\VideoFeedback;
use App\models\VideoLimbo;
use App\models\VideoLive;
use App\models\VideoLiveChats;
use App\models\VideoLiveFeedBack;
use App\models\VideoLiveGuest;
use App\models\VideoPlaceRelation;
use App\models\VideoTagRelation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\models\History;
use App\models\Servers;
use Illuminate\Support\Facades\DB;
//use Intervention\Image\Image;

class StreamingController extends Controller
{

	private $sharedKey = "kia20#AndMohammad10#";

    public function importVideoToDB()
    {
        $loc = __DIR__ . '/../../../../assets/_images/video';

        $videos = scandir($loc);
        foreach ($videos as $video) {
            $vidLoc = $loc . '/' . $video;
            if (is_file($vidLoc)) {
                $thumbnailName = explode('.', $video)[0] . '.jpg';

                $nVid = new Video();
                $nVid->userId = auth()->user()->id;
                $nVid->title = explode('.', $video)[0];
                $nVid->description = '';
                $nVid->video = $video;
                $nVid->categoryId = 1;
                $nVid->subtitle = null;
                $nVid->thumbnail = $thumbnailName;

                $ffprobe = \FFMpeg\FFProbe::create();
                $duration = (int)$ffprobe->format($vidLoc)->get('duration');
                $second = $duration % 60;
                $duration = (int)($duration / 60);
                $min = $duration % 60;
                $duration = (int)($duration / 60);

                if ($second < 10)
                    $second = '0' . $second;
                if ($min < 10)
                    $min = '0' . $min;
                $nVid->duration = $duration . ':' . $min . ':' . $second;

                $nVid->save();

                $nloc = $loc . '/' . auth()->user()->id;
                if (!is_dir($nloc))
                    mkdir($nloc);

                rename($loc . '/' . $video, $nloc . '/' . $video);

                $vidLoc = $nloc . '/' . $video;

                $ffmpeg = \FFMpeg\FFMpeg::create();
                $video = $ffmpeg->open($vidLoc);
                $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(42));
                $frame->save($nloc . '/' . $thumbnailName);
            }
        }
        dd('done');
    }

    public function setVideoDuration()
    {
        $video = Video::all();
        foreach ($video as $item) {
            $loc = __DIR__ . '/../../../../assets/_images/video/' . $item->userId . '/' . $item->video;

            $ffprobe = \FFMpeg\FFProbe::create();
            $duration = (int)$ffprobe->format($loc)->get('duration');
            $second = $duration % 60;
            $duration = (int)($duration / 60);
            $min = $duration % 60;
            $duration = (int)($duration / 60);

            if ($second < 10)
                $second = '0' . $second;
            if ($min < 10)
                $min = '0' . $min;
            $item->duration = $duration . ':' . $min . ':' . $second;
            $item->save();
        }

        dd('done');
    }

    public function run() {

    echo "called" . "\n";
    $servers = DB::select("select * from servers order by rand()");

    if($servers == null || count($servers) == 0)
            return;

    $videos = Video::whereConfirm(1)->whereNull('link')->whereStatus(-1)->skip(0)->take(2)->get();
    //$videos = Video::whereConfirm(1)->whereNull('link')->whereStatus(-1)->get();
    $serverIdx = 0;
    $last_fetches = [];

    for($i = 0; $i < count($servers); $i++)
            $last_fetches[$i] = -1;

    for ($i = 0; $i < count($videos); $i++) {
            $videos[$i]->status = 0;
        /*
        $filepath = __DIR__ . '/../../../../assets/_images/video/' . $videos[$i]->userId . '/' . $videos[$i]->video;
        $res = shell_exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 " . $filepath);
        if(strpos($res, 'x') !== false && strlen($res) < 10) {
            $res = str_replace("\n", "", $res);
            $tmp = explode("x", $res);
            $res = $tmp[1];
            $videos[$i]->first_res = 1;

            if($res >= 240)
                $videos[$i]->second_res = 1;

            if($res >= 360)
                $videos[$i]->third_res = 1;

            if($res >= 480)
                $videos[$i]->forth_res = 1;

            if($res >= 720)
                $videos[$i]->fifth_res = 1;

            if($res >= 1080)
                $videos[$i]->sixth_res = 1;

                $videos[$i]->save();
        }
        */
            $videos[$i]->save();
    }

    //dd("finish");

    for ($i = 0; $i < count($videos); $i++) {

    if ($serverIdx >= count($servers))
            $serverIdx = 0;

    if(time() - $last_fetches[$serverIdx] < 600)
            sleep(600 + $last_fetches[$serverIdx] - time());

    $serverIP = $servers[$serverIdx]->ip;
    $filepath = __DIR__ . '/../../../../assets/_images/video/' . $videos[$i]->userId . '/' . $videos[$i]->video;

    if(!file_exists($filepath))
        dd("file not found " . $filepath);

    shell_exec('sshpass -p "ubuntu" scp ' . $filepath . ' root@185.141.169.171:/var/www/cs/rawFiles/' . $videos[$i]->video);

//    $cfile = curl_file_create($filepath, 'application/octet-stream','1.mp4'); // try adding

    $time = time();
    $hash = hash("sha256", $this->sharedKey . $time);
//    $data = array('file' => $cfile, 'time' => $time, 'digest' => $hash, 'videoId' => $videos[$i]->id);
    $data = array('file' => $videos[$i]->video, 'time' => $time, 'digest' => $hash, 'videoId' => $videos[$i]->id);
    $data += ["first_res" => ($videos[$i]->first_res) ? "ok" : "nok"];
    $data += ["second_res" => ($videos[$i]->second_res) ? "ok" : "nok"];
    $data += ["third_res" => ($videos[$i]->third_res) ? "ok" : "nok"];
    $data += ["forth_res" => ($videos[$i]->forth_res) ? "ok" : "nok"];
    $data += ["fifth_res" => ($videos[$i]->fifth_res) ? "ok" : "nok"];
    $data += ["sixth_res" => ($videos[$i]->sixth_res) ? "ok" : "nok"];
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_URL, 'http://185.141.169.171:8080');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data2 = curl_exec($ch);

    if ($data2 === false)
        echo curl_error($ch);
    else
        echo "\n has been sent \n";
    //echo $data2;
    curl_close($ch);
    $last_fetches[$serverIdx] = time();
    $serverIdx++;
    }
    }

    public function updateLink() {

        if(isset($_POST["newLink"]) && isset($_POST["time"]) && isset($_POST["digest"]) && isset($_POST["videoId"])) {

                $time = $_POST["time"];
		if(time() - $time > 180) {
			echo "nok4";
			return;
		}

		$digest = $_POST["digest"];
		$hash = hash("sha256", $this->sharedKey . $time);

                if($hash != $digest) {
                        echo "nok3";
                        return;
                }

                $newLink = $_POST["newLink"];
                $videoId = $_POST["videoId"];
                $video = Video::whereId($videoId);

                if($video == null) {
                        echo "nok1";
                        return;
                }

                $ip1 = $_SERVER['REMOTE_ADDR'];

                $server = DB::select("select id from servers where ip = '" . $ip1 . "'");
                if($server == null || count($server) == 0) {
                        echo "nok2";
                        return;
                }
                $server = $server[0];
                //try {
                //if (file_exists("/var/www/" . $video->video))
                //        unlink("/var/www/" . $video->video);
                //}
                //catch(\Exception $x) {}

                $video->link = $newLink;
                $video->save();

                $hist = new History();
                $hist->server_id = $server->id;
                $hist->video_id = $video->id;
                $hist->save();
                echo "ok";
                return;
        }
        echo "nok2";
}


}
