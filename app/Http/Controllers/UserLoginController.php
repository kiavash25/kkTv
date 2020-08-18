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
        return redirect(route('index'));
    }

    public function mainDoLogin()
    {
        echo 'ok';
        return;
    }

    public function doLogin()
    {
        if (isset($_POST["username"]) && isset($_POST["password"])) {

            $username = makeValidInput($_POST['username']);
            $password = makeValidInput($_POST['password']);

            $credentials  = ['username' => $username, 'password' => $password];
            $credentialsPhone  = ['phone' => $username, 'password' => $password];
            $credentialsEmail  = ['email' => $username, 'password' => $password];

            if (Auth::attempt($credentials, true) || Auth::attempt($credentialsPhone, true) || Auth::attempt($credentialsEmail, true)) {
                $user = Auth::user();
                if ($user->status != 0) {
                    RetrievePas::where('uId', Auth::user()->id)->delete();
                    if(!Auth::check())
                        Auth::login($user);

                    echo "ok";
                }
                else {
                    auth()->logout();
                    echo "nok2";
                }
            }
        }
        else
            echo "nok";

        return;
    }

    public function checkLogin() {
        if(!Auth::check()) {
            if (isset($_POST["username"]) && isset($_POST["password"])) {
                $username = makeValidInput($_POST['username']);
                $password = makeValidInput($_POST['password']);
                $credentials  = ['username' => $username, 'password' => $password];
                if (Auth::attempt($credentials, true)) {
                    $user = Auth::user();
                    if ($user->status != 0) {
                        if(!Auth::check())
                            Auth::login($user);
                    }
                }
            }
        }

        return \redirect(url($_POST['redirectUrl']));
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        $back = \redirect()->back();
        if(\Session::has('lang'))
            $language = \Session::get('lang');

        Auth::logout();
        \Session::flush();

        if(isset($language))
            \Session::put('lang', $language);

        return \redirect()->back();
    }

    public function checkEmail()
    {
        if (isset($_POST["email"]) && $_POST['email'] != '') {
            if(\auth()->check())
                echo (User::where('email', makeValidInput($_POST["email"]))->where('id', '!=', \auth()->user()->id)->count() > 0) ? 'nok' : 'ok';
            else
                echo (User::where('email', makeValidInput($_POST["email"]))->count() > 0) ? 'nok' : 'ok';
            return;
        }
        echo "nok1";
        return;
    }

    public function checkRegisterData(Request $request)
    {
        if(isset($request->name) && isset($request->emailPhone)){
            $nameErr = false;
            $emailErr = false;
            $phoneErr = false;

            if(strpos($request->emailPhone, '@') > -1) {
                $checkEmail = User::where('email', $request->emailPhone)->first();
                if($checkEmail != null)
                    $emailErr = true;
            }
            else{
                $phone = convertNumber('en', $request->emailPhone);
                $checkPhone = User::where('phone', $phone)->first();
                if($checkPhone != null)
                    $phoneErr = true;
            }

            $checkName = User::where('username', $request->name)->first();
            if($checkName != null)
                $nameErr = true;

            echo json_encode(['status' => 'ok', 'nameErr' => $nameErr, 'phoneErr' => $phoneErr, 'emailErr' => $emailErr]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function checkUserName()
    {
        if (isset($_POST["username"])) {

            $invitationCode = "";

            if(\auth()->check()){
                if(User::where('username', makeValidInput($_POST['username']))->where('id', '!=', \auth()->user()->id)->count() > 0)
                    echo 'nok1';
                else
                    echo 'ok';
            }
            else {
                if (isset($_POST["invitationCode"]))
                    $invitationCode = makeValidInput($_POST["invitationCode"]);

                if (User::where('username', makeValidInput($_POST["username"]))->count() > 0)
                    echo "nok1";
                else if (!empty($invitationCode) && User::where('invitationCode', $invitationCode)->count() == 0)
                    echo 'nok';
                else
                    echo 'ok';
            }

            return;
        }
        echo "nok";
    }

    public function checkPhoneNum()
    {
        if (isset($_POST["phoneNum"])) {

            $phoneNum = makeValidInput($_POST["phoneNum"]);
            $phoneNum = convertNumber('en', $phoneNum);

            if(\auth()->check()){
                if (User::where('phone', $phoneNum)->where('id', '!=', \auth()->user()->id)->count() > 0)
                    echo 'nok';
                else
                    echo 'ok';
            }
            else {
                if (User::where('phone', $phoneNum)->count() > 0)
                    echo json_encode(['status' => 'nok']);
                else {
                    $activation = ActivationCode::where('phoneNum', $phoneNum)->first();
                    if ($activation != null) {
                        if((90 - time() + $activation->sendTime) < 0)
                            $this->resendActivationCode();
                        else
                            echo json_encode(['status' => 'ok', 'reminder' => (90 - time() + $activation->sendTime)]);

                        return;
                    }

                    $code = createCode();
                    while (ActivationCode::where('code', $code)->count() > 0)
                        $code = createCode();

                    if ($activation == null) {
                        $activation = new ActivationCode();
                        $activation->phoneNum = $phoneNum;
                        $activation->userId = 0;
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
            $phoneNum = convertNumber('en', $_POST["phoneNum"]);
            $code = convertNumber('en', $_POST["activationCode"]);
            $condition = ['code' => $code, 'phoneNum' => $phoneNum];
            $activation = ActivationCode::where($condition)->first();
            if ($activation != null) {
                echo "ok";
                return;
            }
        }
        echo "nok";
        return;
    }

    public function resendActivationCode()
    {
        if (isset($_POST["phoneNum"])) {

            $phoneNum = makeValidInput($_POST["phoneNum"]);
            $activation = ActivationCode::where('phoneNum', $phoneNum)->first();

            if ($activation != null) {

                $t = $activation->sendTime;
                if (time() - $t < 90) {
                    echo json_encode(['status' => 'nok', 'reminder' => (90 - time() + $t)]);
                    return;
                } else {

                    $code = createCode();
                    while (ActivationCode::where('code', $code)->count() > 0)
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

            $user = User::where('phone', makeValidInput($phoneNum))->first();

            if ($user != null) {

                $retrievePas = RetrievePas::where('uId', $user->id)->first();

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

    public function registerAndLogin(Request $request)
    {
        if (isset($_POST["username"]) && isset($_POST["password"]) &&
            (isset($_POST["email"]) || isset($_POST["phone"]))) {

            $uInvitationCode = createCode();
            while (User::where('invitationCode', $uInvitationCode)->count() > 0)
                $uInvitationCode = createCode();

            $checkUserName = User::where('username', $request->username)->count();
            if($checkUserName > 0){
                echo 'nok1';
                return;
            }

            if(isset($request->phone) && $request->phone != ''){
                if(isset($request->actCode)) {
                    $phone = convertNumber('en', $request->phone);
                    $actCode = convertNumber('en', $request->actCode);
                    $check = ActivationCode::where('phoneNum', $phone)->where('code', $actCode)->first();
                    if ($check == null) {
                        echo 'nok5';
                        return;
                    }
                    $check->delete();
                }
                else{
                    echo 'nok5';
                    return;
                }
            }
            else if(isset($_POST["email"]) && $_POST["email"] != ''){

            }
            else{
                echo 'nok10';
                return;
            }

            $invitationCode = "";
            if (isset($request->invitationCode) && $request->invitationCode != '') {
                $invitationCode = makeValidInput($request->invitationCode);
                if (!empty($invitationCode)) {
                    $dest = User::where('invitationCode', $invitationCode)->first();
                    if ($dest == null) {
                        echo 'nok3';
                        return;
                    }
                }
            }

            $user = new User();
            $user->username = makeValidInput($request->username);
            $user->password = \Hash::make(makeValidInput($request->password));
            $user->email = makeValidInput($request->email);
            $user->phone = convertNumber('en', makeValidInput($request->phone));
            $user->invitationCode = $uInvitationCode;
            $user->level = 0;

            try {
                $user->save();
            } catch (Exception $x) {
                echo "nok1 " . $x->getMessage();
                return;
            }

//            try {
//                if (isset($request->email))
//                    welcomeEmail($user->username, $user->email);
//            } catch (Exception $x) {
//                echo "nok2";
//                return;
//            }

            if (isset($dest) && $dest != null && isset($request->invitationCode) && $request->invitationCode != null ) {
                $log = new LogModel();
                $log->visitorId = $user->id;
                $log->date = date('Y-m-d');
                $log->time = getToday()["time"];
                $log->activityId = Activity::where('name', 'دعوت')->first()->id;
                $log->kindPlaceId = -1;
                $log->confirm = 1;
                $log->placeId = -1;
                try {
                    $log->save();
                } catch (Exception $x) {}

                $log = new LogModel();
                $log->visitorId = $dest->id;
                $log->date = date('Y-m-d');
                $log->time = getToday()["time"];
                $log->activityId = Activity::where('name', 'دعوت')->first()->id;
                $log->kindPlaceId = -1;
                $log->confirm = 1;
                $log->placeId = -1;
                try {
                    $log->save();
                } catch (Exception $x) {}
            }

            Auth::loginUsingId($user->id);
            echo "ok";
        }
        else
            echo "nok";

        return;
    }

    public function retrievePasByEmail()
    {
        if (isset($_POST["email"])) {
            $email = makeValidInput($_POST["email"]);
            $user = User::where('email', $email)->first();

            if ($user != null) {

                $code = generateRandomString(10);
                while(RetrievePas::where('code', $code)->count() != 0)
                    $code = generateRandomString(10);

                $ret = RetrievePas::where('email', $email)->first();
                if($ret != null){
                    if(300 + $ret->sendTime < time()){
                        $ret->code = $code;
                        $ret->sendTime = time();
                        $ret->save();
                    }
                    else{
                        echo json_encode(['status' => 'nok3', 'remainder' => 300 + $ret->sendTime - time()]);
                        return;
                    }
                }
                else {
                    $ret = new RetrievePas();
                    $ret->uId = $user->id;
                    $ret->code = $code;
                    $ret->email = $_POST['email'];
                    $ret->sendTime = time();
                    $ret->save();
                }

                $link = route('newPasswordEmail', ['code' => $code]);
                try {
                    forgetPassEmail($user->username, $link, $user->email);
                    echo json_encode(['status' => 'ok', 'remainder' => 300 + $ret->sendTime - time()]);
                }
                catch (\Exception $exception){
                    $ret->delete();
                    echo json_encode(['status' => 'nok2']);
                }
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function newPasswordEmailPage($code)
    {
        $retPass = RetrievePas::where('code', $code)->first();
        if( !($retPass != null && (300 - time() + $retPass->sendTime) > 0) ) {
            if($retPass != null)
                $retPass->delete();

            return \redirect(url('index'));
        }

        return view('pages.newPasswordEmail', compact(['code']));
    }

    public function setNewPasswordEmail(Request $request)
    {
        if(isset($request->code) && isset($request->password)){
            $retPass = RetrievePas::where('code', $request->code)->first();
            if($retPass != null){
                $user = User::find($retPass->uId);
                $user->password = \Hash::make($request->password);
                $user->save();

                $retPass->delete();

                echo 'ok';
            }
            else
                echo 'nok1';
        }
        else
            echo 'nok';

        return;
    }

    public function retrievePasByPhone()
    {
        if (isset($_POST["phone"])) {
            $phoneNum = convertNumber('en', $_POST["phone"]);
            $user = User::where('phone', makeValidInput($phoneNum))->first();

            if ($user != null) {
                $activeCode = ActivationCode::where('userId', $user->id)->where('phoneNum', $phoneNum)->first();
                if ($activeCode != null && (90 - time() + $activeCode->sendTime) > 0) {
                    echo json_encode(['status' => 'ok', 'reminder' => 90 - time() + $activeCode->sendTime]);
                    return;
                }

                $code = createCode();
                while (ActivationCode::where('code', $code)->count() > 0)
                    $code = createCode();
                $msgId = sendSMS($user->phone, $code, 'sms');
                if ($msgId != -1) {
                    $activeCode = ActivationCode::where('userId', $user->id)->where('phoneNum', $phoneNum)->first();
                    if($activeCode == null) {
                        $activeCode = new ActivationCode();
                        $activeCode->userId = $user->id;
                        $activeCode->phoneNum = $user->phone;
                    }
                    $activeCode->sendTime = time();
                    $activeCode->code = $code;
                    $activeCode->save();

                    echo json_encode(['status' => 'ok', 'reminder' => 90]);
                }
                else
                    echo json_encode(['status' => 'nok2', 'reminder' => 90]);

                return;
            }
            else
                echo json_encode(['status' => 'nok1', 'reminder' => 90]);
        }
        echo json_encode(['status' => 'nok', 'reminder' => 90]);
        return;
    }

    public function registerAndLogin2()
    {
        if (isset($_POST["username"]) && isset($_POST["password"])) {

            $invitationCode = createCode();
            while (User::where('invitationCode', $invitationCode)->count() > 0)
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
                    $dest = User::where('invitationCode', $invitationCode)->first();

                    if ($dest != null) {
                        $log = new LogModel();
                        $log->visitorId = $user->id;
                        $log->date = date('Y-m-d');
                        $log->activityId = Activity::where('name', 'دعوت')->first()->id;
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
                        $log->activityId = Activity::where('name', 'دعوت')->first()->id;
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
        $url = route('index');
        if(isset($_GET['state']))
            $url = $_GET['state'];

        if (Auth::check())
            return \Redirect::to($url);

        if (isset($_GET['code'])) {

            require_once __DIR__ . '/glogin/libraries/Google/autoload.php';

            //Insert your cient ID and sexcret
            //You can get it from : https://console.developers.google.com/
            $client_id = env('GOOGLE_CLIENT_ID');
            $client_secret = env('GOOGLE_CLIENT_SECRET2');
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

        return redirect(url($url));
    }

    public function registerWithPhone(Request $request)
    {
        if (isset($request->username) && isset($request->password) && isset($request->phone)) {

            $invitationCode = createCode();
            while (User::where('invitationCode', $invitationCode)->count() > 0)
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
                        $dest = User::where('invitationCode', $invitationCode)->first();

                        if ($dest != null) {
                            $log = new LogModel();
                            $log->visitorId = $user->id;
                            $log->date = date('Y-m-d');
                            $log->activityId = Activity::where('name', 'دعوت')->first()->id;
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
                            $log->activityId = Activity::where('name', 'دعوت')->first()->id;
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

    public function setNewPassword(Request $request)
    {
        if(isset($request->pass) && isset($request->phone) && isset($request->code)){
            $checkPhone = ActivationCode::where('phoneNum', $request->phone)->where('code', $request->code)->first();
            if($checkPhone != null && ($checkPhone->sendTime + 600) - time() > 0 ){
                $user = User::where('phone', $request->phone)->first();
                if($user != null && $checkPhone->userId == $user->id){
                    $user->password = \Hash::make($request->pass);
                    $user->save();
                    $checkPhone->delete();

                    echo 'ok';
                }
                else
                    echo 'nok1';
            }
            else
                echo 'nok5';
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
