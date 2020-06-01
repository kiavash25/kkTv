<?php

namespace App\Http\Controllers;

use App\models\ActivationCode;
use App\models\Activity;
use App\models\LogModel;
use App\models\RetrievePas;
use App\models\User;
use Auth;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function login()
    {
        return redirect(route('main'));
    }

    public function mainDoLogin()
    {

        if (isset($_POST["username"]) && isset($_POST["password"])) {

            $username = makeValidInput($_POST['username']);
            $password = makeValidInput($_POST['password']);

            if (Auth::attempt(['username' => $username, 'password' => $password], true)) {

                if(Auth::user()->status != 0) {
                    RetrievePas::whereUId(Auth::user()->id)->delete();
                    return \Redirect::intended('/');
                }
            }
        }

        return \Redirect::route('main');
    }

    public function doLogin()
    {
        if (isset($_POST["username"]) && isset($_POST["password"])) {

            $username = makeValidInput($_POST['username']);
            $password = makeValidInput($_POST['password']);

            $credentials  = ['username' => $username, 'password' => $password];

            if (Auth::attempt($credentials, true)) {
                $user = Auth::user();
                if ($user->status != 0) {
                    RetrievePas::whereUId(Auth::user()->id)->delete();

                    if(!Auth::check())
                        Auth::login($user);

                    echo "ok";
                    return;
                }
                else {
                    auth()->logout();
                    echo "nok2";
                    return;
                }
            }
        }
        else
            echo "nok";
    }

    public function checkLogin() {

        if(Auth::check()) {
            return \redirect()->back();
        }
        else{
            if (isset($_POST["username"]) && isset($_POST["password"])) {

                $username = makeValidInput($_POST['username']);
                $password = makeValidInput($_POST['password']);

                $credentials  = ['username' => $username, 'password' => $password];

                if (Auth::attempt($credentials, true)) {

                    $user = Auth::user();
                    if ($user->status != 0) {

                        if(!Auth::check()) {
                            Auth::login($user);
                        }
                        return \redirect()->back();

                    } else {
                        return \redirect()->back();
                    }
                }
            }
        }
    }

    public function logout()
    {
        Auth::logout();
//        \Session::flush();
        return \redirect()->back();
    }

    public function checkEmail()
    {
        if (isset($_POST["email"]) && $_POST['email'] != '') {
            if(\auth()->check())
                echo (User::whereEmail(makeValidInput($_POST["email"]))->where('id', '!=', \auth()->user()->id)->count() > 0) ? 'nok' : 'ok';
            else
                echo (User::whereEmail(makeValidInput($_POST["email"]))->count() > 0) ? 'nok' : 'ok';
            return;
        }
        echo "nok1";
        return;
    }

    public function checkUserName()
    {
        if (isset($_POST["username"])) {

            $invitationCode = "";

            if(\auth()->check()){
                if(User::whereUserName(makeValidInput($_POST['username']))->where('id', '!=', \auth()->user()->id)->count() > 0)
                    echo 'nok1';
                else
                    echo 'ok';
            }
            else {
                if (isset($_POST["invitationCode"]))
                    $invitationCode = makeValidInput($_POST["invitationCode"]);

                if (User::whereUserName(makeValidInput($_POST["username"]))->count() > 0)
                    echo "nok1";
                else if (!empty($invitationCode) && User::whereInvitationCode($invitationCode)->count() == 0)
                    echo 'nok';
                else
                    echo 'ok';
            }

            return;
        }
        echo "nok";
    }

    public function checkReCaptcha()
    {
        echo 'ok';
        return;

        if (isset($_POST["captcha"])) {
            $response = $_POST["captcha"];
            $privatekey = "6LfiELsUAAAAALYmxpnjNQHcEPlhQdbGKpNpl7k4";

            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$privatekey}&response={$response}");
            $captcha_success = json_decode($verify);
            if ($captcha_success->success == true)
                echo "ok";
            else
                echo "nok2";

            return;
        }
        echo "nok";
    }

    public function checkActivationCode()
    {

        if (isset($_POST["activationCode"]) && isset($_POST["phoneNum"])) {

            $phoneNum = $_POST["phoneNum"];
            $phoneNum = convertNumber('en', $phoneNum);

            $condition = ['code' => makeValidInput($_POST["activationCode"]),
                'phoneNum' => makeValidInput($phoneNum)];

            $activation = ActivationCode::where($condition)->first();
            if ($activation != null) {
                $activation->delete();
                echo "ok";
                return;
            }
        }
        echo "nok";
    }

    public function resendActivationCode()
    {
        if (isset($_POST["phoneNum"])) {

            $phoneNum = makeValidInput($_POST["phoneNum"]);
            $activation = ActivationCode::wherePhoneNum($phoneNum)->first();

            if ($activation != null) {

                $t = $activation->sendTime;
                if (time() - $t < 90) {
                    echo json_encode(['status' => 'nok', 'reminder' => (90 - time() + $t)]);
                    return;
                } else {

                    $code = createCode();
                    while (ActivationCode::whereCode($code)->count() > 0)
                        $code = createCode();

                    $msgId = sendSMS($phoneNum, $code, 'sms');

                    if ($msgId == -1) {
                        echo json_encode(['status' => 'nok3', 'reminder' => 90]);
                        return;
                    }

                    $activation->sendTime = time();
                    $activation->code = $code;
                    try {
                        $activation->save();
                        echo json_encode(['status' => 'ok', 'reminder' => 90]);;
                        return;
                    } catch (Exception $x) {
                    }
                }
            }
            echo json_encode(['status' => 'nok', 'reminder' => 90]);
        }
    }

    public function resendActivationCodeForget()
    {

        if (isset($_POST["phoneNum"])) {
            $phoneNum = $_POST["phoneNum"];
            $phoneNum = convertNumber('en', $phoneNum);

            $user = User::wherePhone(makeValidInput($phoneNum))->first();

            if ($user != null) {

                $retrievePas = RetrievePas::whereUId($user->id)->first();

                if ($retrievePas == null) {
                    echo json_encode(['status' => 'nok4', 'reminder' => 90]);
                    return;
                }

                if (time() - $retrievePas->sendTime < 90) {
                    echo json_encode(['status' => 'nok', 'reminder' => (90 - time() + $retrievePas->sendTime)]);
                    return;
                }

                $newPas = $this->generatePassword();
                $msgId = sendSMS($user->phone, $newPas, 'sms');

                if ($msgId != -1) {
                    $user->password = \Hash::make($newPas);
                    $retrievePas->sendTime = time();
                    try {
                        $user->save();
                        $retrievePas->save();
                        echo json_encode(['status' => 'ok', 'reminder' => 90]);
                    } catch (Exception $x) {
                    }
                } else {
                    echo json_encode(['status' => 'nok2', 'reminder' => 90]);
                }
                return;
            }
        }
        echo json_encode(['status' => 'nok3', 'reminder' => 90]);
    }

    public function checkPhoneNum()
    {

        if (isset($_POST["phoneNum"])) {

            $phoneNum = makeValidInput($_POST["phoneNum"]);
            $phoneNum = convertNumber('en', $phoneNum);

            if(\auth()->check()){
                if (User::wherePhone($phoneNum)->where('id', '!=', \auth()->user()->id)->count() > 0)
                    echo 'nok';
                else
                    echo 'ok';
            }
            else {
                if (User::wherePhone($phoneNum)->count() > 0)
                    echo json_encode(['status' => 'nok']);
                else {

                    $activation = ActivationCode::wherePhoneNum($phoneNum)->first();
                    if ($activation != null) {
                        if((90 - time() + $activation->sendTime) < 0)
                            $this->resendActivationCode();
                        else
                            echo json_encode(['status' => 'ok', 'reminder' => (90 - time() + $activation->sendTime)]);

                        return;
                    }

                    $code = createCode();
                    while (ActivationCode::whereCode($code)->count() > 0)
                        $code = createCode();

                    if ($activation == null) {
                        $activation = new ActivationCode();
                        $activation->phoneNum = $phoneNum;
                    }

                    $msgId = sendSMS($phoneNum, $code, 'sms');
                    if ($msgId == -1) {
                        echo json_encode(['status' => 'nok3']);
                        return;
                    }

                    $activation->sendTime = time();
                    $activation->code = $code;
                    try {
                        $activation->save();
                        echo json_encode(['status' => 'ok', 'reminder' => 90]);
                    } catch (Exception $x) {
                    }
                }
            }
            return;
        }
        echo json_encode(['status' => 'nok']);
    }

    public function registerAndLogin()
    {

        if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {

            $invitationCode = createCode();
            while (User::whereInvitationCode($invitationCode)->count() > 0)
                $invitationCode = createCode();

            $user = new User();
            $user->username = makeValidInput($_POST["username"]);
            $user->password = \Hash::make(makeValidInput($_POST["password"]));
            $user->email = makeValidInput($_POST["email"]);
            $user->level = 0;
            $user->created_at = date('Y-m-d h:m:s');
            $user->updated_at = date('Y-m-d h:m:s');
            $user->invitationCode = $invitationCode;

            try {
                $user->save();

                Auth::attempt(['username' => makeValidInput($_POST["username"]), 'password' => makeValidInput($_POST["password"])], true);

                $invitationCode = "";

                if (isset($_POST["invitationCode"]))
                    $invitationCode = makeValidInput($_POST["invitationCode"]);

                if (!empty($invitationCode)) {
                    $dest = User::whereInvitationCode($invitationCode)->first();

                    if ($dest != null) {
                        $log = new LogModel();
                        $log->visitorId = $user->id;
                        $log->date = date('Y-m-d');
                        $log->time = getToday()["time"];
                        $log->activityId = Activity::whereName('دعوت')->first()->id;
                        $log->kindPlaceId = -1;
                        $log->confirm = 1;
                        $log->placeId = -1;
                        try {
                            $log->save();
                        } catch (Exception $x) {
                            echo $x->getMessage();
                            return;
                        }

                        $log = new LogModel();
                        $log->visitorId = $dest->id;
                        $log->date = date('Y-m-d');
                        $log->time = getToday()["time"];
                        $log->activityId = Activity::whereName('دعوت')->first()->id;
                        $log->kindPlaceId = -1;
                        $log->confirm = 1;
                        $log->placeId = -1;
                        try {
                            $log->save();
                        } catch (Exception $x) {
                            echo $x->getMessage();
                            return;
                        }
                    }
                }

                echo "ok";
                return;
            } catch (Exception $x) {
                echo "nok " . $x->getMessage();
                return;
            }
        }

        echo "nok2";
    }

    public function retrievePasByEmail()
    {

        if (isset($_POST["email"])) {

            $email = makeValidInput($_POST["email"]);

            $user = User::whereEmail($email)->where('link', '', '')->first();

            if ($user != null) {

                $newPas = $this->generatePassword();
                $user->password = \Hash::make($newPas);

                try {
                    $text = 'رمزعبور جدید شما در سایت کوچیتا:' . '<br/>' . $newPas .
                        '<center>به ما سر بزنید</center><br/><center><a href="www.shazdemosafer.com">www.shazdemosafer.com</a></center>';
                    if (sendMail($text, $email, 'بازیابی رمزعبور'))
                        echo "ok";
                    else
                        echo "nok2";

                    $user->save();
                } catch (Exception $x) {
                    echo $x->getMessage();
                }

                return;
            }
        }
        echo "nok";
    }

    public function retrievePasByPhone()
    {

        if (isset($_POST["phone"])) {

            $user = User::wherePhone(makeValidInput($_POST["phone"]))->first();

            if ($user != null) {

                $retrievePas = RetrievePas::whereUId($user->id)->first();

                if ($retrievePas != null) {
                    echo json_encode(['status' => 'ok', 'reminder' => 90 - time() + $retrievePas->sendTime]);
                    return;
                }

                $newPas = $this->generatePassword();
                $msgId = sendSMS($user->phone, $newPas, 'pass');

                if ($msgId != -1) {

                    $retrievePas = new RetrievePas();
                    $retrievePas->uId = $user->id;
                    $user->password = \Hash::make($newPas);
                    $retrievePas->sendTime = time();

                    try {
                        $user->save();
                        $retrievePas->save();
                        echo json_encode(['status' => 'ok', 'reminder' => 90]);
                    } catch (Exception $x) {
                    }
                } else {
                    echo json_encode(['status' => 'nok2', 'reminder' => 90]);
                }
                return;
            }
        }
        echo json_encode(['status' => 'nok', 'reminder' => 90]);
    }

    public function registerAndLogin2()
    {
        if (isset($_POST["username"]) && isset($_POST["password"])) {

            $invitationCode = createCode();
            while (User::whereInvitationCode($invitationCode)->count() > 0)
                $invitationCode = createCode();

            $user = new User();
            $user->username = makeValidInput($_POST["username"]);
            $user->password = \Hash::make(makeValidInput($_POST["password"]));
            $user->email = makeValidInput($_POST["email"]);
            $user->level = 0;
            $user->created_at = date('Y-m-d h:m:s');
            $user->updated_at = date('Y-m-d h:m:s');
            $user->invitationCode = $invitationCode;

            try {
                $user->save();

                Auth::attempt(array('username' => makeValidInput($_POST["username"]), 'password' => makeValidInput($_POST["password"])), true);

                $invitationCode = makeValidInput($_POST["invitationCode"]);

                if (!empty($invitationCode)) {
                    $dest = User::whereInvitationCode($invitationCode)->first();

                    if ($dest != null) {
                        $log = new LogModel();
                        $log->visitorId = $user->id;
                        $log->date = date('Y-m-d');
                        $log->activityId = Activity::whereName('دعوت')->first()->id;
                        $log->kindPlaceId = -1;
                        $log->time = getToday()["time"];
                        $log->confirm = 1;
                        $log->placeId = -1;
                        try {
                            $log->save();
                        } catch (Exception $x) {
                            echo $x->getMessage();
                            return;
                        }

                        $log = new LogModel();
                        $log->visitorId = $dest->id;
                        $log->date = date('Y-m-d');
                        $log->time = getToday()["time"];
                        $log->activityId = Activity::whereName('دعوت')->first()->id;
                        $log->kindPlaceId = -1;
                        $log->confirm = 1;
                        $log->placeId = -1;
                        try {
                            $log->save();
                        } catch (Exception $x) {
                            echo $x->getMessage();
                            return;
                        }
                    }
                }

                echo "ok";
                return;
            } catch (Exception $x) {
                dd($x);
                echo "nok";
                return;
            }
        }

        echo "nok";
    }

    public function loginWithGoogle()
    {

        if (Auth::check())
            return \Redirect::to(route('main'));

        if (isset($_GET['code'])) {

            require_once __DIR__ . '/glogin/libraries/Google/autoload.php';

            //Insert your cient ID and sexcret
            //You can get it from : https://console.developers.google.com/
            $client_id = '774684902659-1tdvb7r1v765b3dh7k5n7bu4gpilaepe.apps.googleusercontent.com';
            $client_secret = '8NM4weptz-Pz-6gbolI5J0yi';
            $redirect_uri = route('loginWithGoogle');

            /************************************************
             * Make an API request on behalf of a user. In
             * this case we need to have a valid OAuth 2.0
             * token for the user, so we need to send them
             * through a login flow. To do this we need some
             * information from our API console project.
             ************************************************/
            $client = new \Google_Client();
            $client->setClientId($client_id);
            $client->setClientSecret($client_secret);
            $client->setRedirectUri($redirect_uri);
            $client->addScope("email");
            $client->addScope("profile");

            /************************************************
             * When we create the service here, we pass the
             * client to it. The client then queries the service
             * for the required scopes, and uses that when
             * generating the authentication URL later.
             ************************************************/
            $service = new \Google_Service_Oauth2($client);

            /************************************************
             * If we have a code back from the OAuth 2.0 flow,
             * we need to exchange that with the authenticate()
             * function. We store the resultant access token
             * bundle in the session, and redirect to ourself.
             */
            $client->authenticate($_GET['code']);

            $user = $service->userinfo->get(); //get user info
            $userCheckEmail = User::where('email', $user->email)->first();
            if($userCheckEmail != null){
                if($userCheckEmail->googleId == null){
                    $userCheckEmail->googleId = $user->id;
                    $userCheckEmail->password = \Hash::make($user->id);
                    try {
                        $userCheckEmail->save();
                    }
                    catch (Exception $x) {
                    }
                }
            }
            else{

                $usernameCheck =  explode('@', $user->email)[0];
                while (true){
                    $checkUser = \App\User::where('username', $usernameCheck)->first();
                    if($checkUser == null)
                        break;
                    else
                        $usernameCheck = explode('@', $user->email)[0] .  random_int(1000, 9999);
                }

                $userCheckEmail = new User();
                $userCheckEmail->username = $usernameCheck;
                $userCheckEmail->password = \Hash::make($user->id);
                $name = explode(' ', $user->name);
                $userCheckEmail->first_name = $name[0];
                $userCheckEmail->last_name = $name[1];
                $userCheckEmail->email = $user->email;
                $userCheckEmail->picture = $user->picture;
                $userCheckEmail->googleId = $user->id;
                try {
                    $userCheckEmail->save();
                }
                catch (Exception $x) {
                }
            }
            Auth::attempt(['username' => $userCheckEmail->username, 'password' => $user->id], true);
        }
        return \Redirect::to(route('main'));
    }

    public function registerWithPhone(Request $request)
    {
        if (isset($request->username) && isset($request->password) && isset($request->phone)) {

            $invitationCode = createCode();
            while (User::whereInvitationCode($invitationCode)->count() > 0)
                $invitationCode = createCode();

            $user = new User();
            $user->username = $request->username;
            $user->password = \Hash::make(makeValidInput($request->password));
            $user->phone = makeValidInput($request->phone);
            $user->first_name = $request->firsName;
            $user->last_name = $request->lastName;
            $user->level = 0;
            $user->invitationCode = $invitationCode;

            try {
                $user->save();
                auth()->loginUsingId($user->id);

                if(isset($_POST["invitationCode"])) {
                    $invitationCode = makeValidInput($_POST["invitationCode"]);

                    if (!empty($invitationCode)) {
                        $dest = User::whereInvitationCode($invitationCode)->first();

                        if ($dest != null) {
                            $log = new LogModel();
                            $log->visitorId = $user->id;
                            $log->date = date('Y-m-d');
                            $log->activityId = Activity::whereName('دعوت')->first()->id;
                            $log->kindPlaceId = -1;
                            $log->time = getToday()["time"];
                            $log->confirm = 1;
                            $log->placeId = -1;
                            try {
                                $log->save();
                            } catch (Exception $x) {
                                echo $x->getMessage();
                                return;
                            }

                            $log = new LogModel();
                            $log->visitorId = $dest->id;
                            $log->date = date('Y-m-d');
                            $log->time = getToday()["time"];
                            $log->activityId = Activity::whereName('دعوت')->first()->id;
                            $log->kindPlaceId = -1;
                            $log->confirm = 1;
                            $log->placeId = -1;
                            try {
                                $log->save();
                            } catch (Exception $x) {
                                echo $x->getMessage();
                                return;
                            }
                        }
                    }
                }

                echo "ok";
            }
            catch (Exception $x) {
                dd($x);
                echo "nok";
            }
        }
        else
            echo 'nok';

        return;
    }

    private function generatePassword()
    {
        $init = 65;
        $init2 = 97;
        $code = "";

        for ($i = 0; $i < 10; $i++) {
            if (rand(0, 1) == 0)
                $code .= chr(rand(0, 25) + $init);
            else
                $code .= chr(rand(0, 25) + $init2);
        }

        return $code;
    }


}
