<?php

use App\models\ActivationCode;
use App\models\DefaultPic;
use App\models\Tags;
use App\User;
use Carbon\Carbon;

function getUserPic($id = 0){

    $user = User::find($id);
    if($user != null){
        if(strpos($user->picture, 'http') !== false)
            return $user->picture;
        else{
            if($user->uploadPhoto == 0){
                $deffPic = DefaultPic::find($user->picture);

                if($deffPic != null)
                    $uPic = \URL::asset('images/defaultPic/' . $deffPic->name);
                else
                    $uPic = \URL::asset('images/blank.jpg');
            }
            else
                $uPic = \URL::asset('images/userProfile/' . $user->picture);
        }
    }
    else
        $uPic = \URL::asset('images/blank.jpg');

    return $uPic;
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


function storeNewTag($tag){
    $check = Tags::where('tag', $tag)->first();
    if($check == null){
        $newTag = new Tags();
        $newTag->tag = $tag;
        $newTag->save();

        return $newTag->id;
    }
    else
        return false;
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


