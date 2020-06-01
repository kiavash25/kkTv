<?php

use App\models\DefaultPic;
use App\User;

function getUserPic($id = 0){

    $user = User::find($id);
    if($user != null){
        if(strpos($user->picture, 'http') !== false)
            return $user->picture;
        else{
            if($user->uploadPhoto == 0){
                $deffPic = DefaultPic::find($user->picture);

                if($deffPic != null)
                    $uPic = asset('images/defaultPic/' . $deffPic->name);
                else
                    $uPic = asset('images/blank.jpg');
            }
            else
                $uPic = asset('images/userProfile/' . $user->picture);
        }
    }
    else
        $uPic = asset('images/blank.jpg');

    return $uPic;
}
