<?php

use App\models\ActivationCode;
use App\models\DefaultPic;
use App\models\Tags;
use App\models\VideoComment;
use App\models\VideoFeedback;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

function getUserPic($id = 0){
    $user = User::find($id);
    if($user != null){
        if(strpos($user->picture, 'http') !== false)
            return $user->picture;
        else{
            if($user->uploadPhoto == 0){
                $deffPic = DefaultPic::find($user->picture);
                if($deffPic != null)
                    $uPic = URL::asset('_images/defaultPic/' . $deffPic->name);
                else
                    $uPic = URL::asset('_images/blank.jpg');
            }
            else
                $uPic = URL::asset('userProfile/' . $user->picture);
        }
    }
    else
        $uPic = URL::asset('images/blank.jpg');

//    dd($uPic);
    return $uPic;
}

function uploadLargeFile($_direction, $_file_data){
    $file_data = decode_chunk($_file_data);
    if ($file_data === false)
        return false;
    else
        file_put_contents($_direction, $file_data, FILE_APPEND);

    return true;
}

function decode_chunk( $data ) {
    $data = explode( ';base64,', $data );
    if ( !is_array($data) || !isset($data[1]))
        return false;
    $data = base64_decode( $data[1] );
    if (!$data)
        return false;
    return $data;
}


function makeValidInput($input) {
    $input = addslashes($input);
    $input = trim($input);
    $input = htmlspecialchars($input);
    return $input;
}

function createCode() {
    $str = "";
    while (true) {
        for ($i = 0; $i < 6; $i++)
            $str .= rand(0, 9);
        if(ActivationCode::whereCode($str)->count() == 0)
            return $str;
    }
}


function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function convertNumber($kind , $number){

    $en = array("0","1","2","3","4","5","6","7","8","9");
    $fa = array("۰","۱","۲","۳","۴","۵","۶","۷","۸","۹");

    if($kind == 'en')
        $number = str_replace($fa, $en, $number);
    else
        $number = str_replace($en, $fa, $number);

    return $number;
}

function getDifferenceTimeString($time){
    $time = Carbon::make($time);

    $diffTimeInMin = Carbon::now()->diffInMinutes($time);

    if($diffTimeInMin <= 15)
        $diffTime = 'هم اکنون';
    else if($diffTimeInMin <= 60)
        $diffTime = 'دقایقی پیش';
    else{
        $diffTimeHour = Carbon::now()->diffInHours($time);
        if($diffTimeHour <= 24)
            $diffTime = $diffTimeHour . ' ساعت پیش ';
        else{
            $diffTimeDay = Carbon::now()->diffInDays($time);
            if($diffTimeDay < 30)
                $diffTime = $diffTimeDay . ' روز پیش ';
            else{
                $diffTimeMonth = Carbon::now()->diffInMonths($time);
                if($diffTimeMonth < 12)
                    $diffTime = $diffTimeMonth . ' ماه پیش ';
                else{
                    $diffYear = (int)($diffTimeMonth / 12);
                    $diffMonth = $diffTimeMonth % 12;
                    $diffTime = $diffYear . ' سال  و ' . $diffMonth . ' ماه پیش ';
                }
            }
        }
    }

    return $diffTime;

}

function sendSMS($destNum, $text, $template, $token2 = "") {

    require_once __DIR__ . '/../../../vendor/autoload.php';

    try{
        $api = new \Kavenegar\KavenegarApi("4836666C696247676762504666386A336846366163773D3D");
        $result = $api->VerifyLookup($destNum, $text, $token2, '', $template);
        if($result){
            foreach($result as $r){
                return $r->messageid;
            }
        }
    }
    catch(\Kavenegar\Exceptions\ApiException $e){
        // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
        echo $e->errorMessage();
        return -1;
    }
    catch(\Kavenegar\Exceptions\HttpException $e){
        // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
        echo $e->errorMessage();
        return -1;
    }
}


//email
function welcomeEmail($username, $email){
    $header = 'به کوچیتا خوش آمدید';
    $userName = $username;
    $view = \View::make('emails.welcomeEmail', compact(['header', 'userName']));
    $html = $view->render();
    if(sendEmail($html, $header, $email))
        return true;
    else
        return false;
}

function forgetPassEmail($userName, $link, $email){
    $header = 'فراموشی رمز عبور';
    $view = \View::make('emails.forgetPass', compact(['header', 'userName', 'link']));
    $html = $view->render();
    if(sendEmail($html, $header, $email))
        return true;
    else
        return false;
}

function sendEmail($text, $subject, $to){
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $text;
        $mail->AltBody = $text;
        $mail->setFrom('support@koochita.com', 'Koochita');
        $mail->addAddress($to);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => true
            )
        );
//        $mail->addReplyTo('ghane@shazdemosafer.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');
        $mail->send();
        return true;

//        $mail->isSMTP();                                      // Set mailer to use SMTP
//        $mail->SMTPAuth = true;             // Enable SMTP authentication
//        $mail->CharSet = 'UTF-8';
//        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
//        $mail->Host = '127.0.0.1';  // Specify main and backup SMTP servers
//        $mail->Username = 'info';                 // SMTP username
//        $mail->Password = 'adeli1982';                           // SMTP password
//        $mail->SMTPOptions = array(
//            'ssl' => array(
//                'verify_peer' => false,
//                'verify_peer_name' => false,
//                'allow_self_signed' => true
//            )
//        );
//        $mail->setFrom( 'info@koochita.com', 'koochita');
//        $mail->addAddress($to);
//        $mail->isHTML(true);                                  // Set email format to HTML
//        $mail->Subject = $subject;
//        $mail->Body = $text;
//        $mail->send();
    }
    catch (Exception $e) {
        return false;
    }
}

function getVideoFullInfo($video, $main = false)
{
    $userLogin = auth()->check();

    $loc = __DIR__.'/../../../../assets/_images/video/' . $video->userId;
    if(is_file($loc .'/min_'. $video->thumbnail))
        $video->pic = \URL::asset('videos/' . $video->userId . '/min_' . $video->thumbnail);
    else
        $video->pic = \URL::asset('videos/' . $video->userId . '/' . $video->thumbnail);

    $video->categoryName = $video->mainCategory[0]->name;
    $video->url = route('video.show', ['code' => $video->code]);
    $video->username = User::find($video->userId)->username;
    $video->userPic = getUserPic($video->userId);
    $video->time = getDifferenceTimeString($video->created_at);

    $video->like = VideoFeedback::where('videoId', $video->id)->whereNull('commentId')->where('like', 1)->count();
    $video->disLike = VideoFeedback::where('videoId', $video->id)->whereNull('commentId')->where('like', -1)->count();
    $video->commentsCount = VideoComment::where('videoId', $video->id)->count();
    $video->comments = [];
    $video->places = [];

    if($video->seen > 1000)
        $video->seen = (floor($video->seen/100)/10) . ' K';

    if($main){
        $video->pic = \URL::asset('videos/' . $video->userId . '/' . $video->thumbnail);
        $resultComment = [];
        $video->comments = getVideoComments($video->id, 0);

        $video->uLike = 0;
        if($userLogin){
            $uLike = VideoFeedback::where('videoId', $video->id)->where('userId', auth()->user()->id)->first();
            if($uLike != null)
                $video->uLike = $uLike->like;
        }
    }

    return $video;
}

function getVideoComments($videoId, $parent){
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
        $comment = getVideoCommentInfos($comment);

    return $comments;
}

function getVideoCommentInfos($_comment){
    $comment = $_comment;
    $ucomment = User::find($comment->userId);
    if ($ucomment != null) {
        $comment->username = $ucomment->username;
        $comment->userPic = getUserPic($ucomment->id);
        $comment->like = VideoFeedback::where('videoId', $comment->videoId)->where('commentId', $comment->id)->where('like', 1)->count();
        $comment->disLike = VideoFeedback::where('videoId', $comment->videoId)->where('commentId', $comment->id)->where('like', -1)->count();
        $comment->time = getDifferenceTimeString($comment->created_at);
        $comment->comments =$comment->haveAns == 1 ? getVideoComments($comment->videoId, $comment->id) : [];

        if($comment->parent != 0)
            $comment->ansToUsername = User::find(VideoComment::find($comment->parent)->userId)->username;
        $comment->ansCount = count($comment->comments);
    }

    return $comment;
}
