<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
if (Auth::check())
    return Redirect::to(route('main'));

require_once(__DIR__ . '/../../../app/Http/Controllers/glogin/libraries/Google/autoload.php');

//Insert your cient ID and secret
//You can get it from : https://console.developers.google.com/
$client_id = config('app.GOOGLE_CLIENT_ID');
$client_secret = config('app.GOOGLE_CLIENT_SECRET');
$redirect_uri = 'https://koochitatv.com/loginWithGoogle';

$client = new \Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

$service = new \Google_Service_Oauth2($client);
$authUrl = $client->createAuthUrl();

$url = $_SERVER['REQUEST_URI'];
$authUrl = str_replace('state', 'state='.$url, $authUrl);
?>
<link rel='stylesheet' type='text/css' href='{{URL::asset('css/component/nloginPopUp.css?v=1')}}'/>

@if(\App::getLocale() == 'en')
    <link rel="stylesheet" href="{{URL::asset('css/ltr/loginPopUpLtr.css?v=2')}}">
@endif

<style>
    .comButLogin {
        color: white;
    }
    .comButLogin:hover{
        color: white;
    }
</style>

{{--loginPopUp--}}
<form id="second_login" method="post" action="{{route('checkLogin')}}">
    {!! csrf_field() !!}
    <input id="form_userName" name="username" type="hidden">
    <input id="form_pass" name="password" type="hidden">
    <input id="redirectUrl" name="redirectUrl" type="hidden">
</form>

<div id="mainLoginPopUp" class="mainLoginPupUpBack hidden">

    <div id="loginPopUp" class="loginPopUpContent">
        <div class="loginHeader row">
            <div class="closeIcon loginCloseIcon" onclick="closeLoginPopup()"></div>
            <img class="loginMainLogo" src="{{URL::asset('images/mainPics/mainIcon.svg')}}">
        </div>

        <div class="loginTextHeader row">
            <div class="text">{{__('در کوچیتا ثبت نام کنید')}}</div>
            <a href="{{$authUrl}}" class="googleA">
                <div class="g-signin2">
                    <div style="height:36px;" class="abcRioButton abcRioButtonLightBlue">
                        <div class="abcRioButtonContentWrapper"
                             style="display: flex; box-shadow: 0 2px 4px 0 rgba(0,0,0,.25); direction: ltr; cursor: pointer">
                            <div class="abcRioButtonIcon" style="padding:8px">
                                <div style="width:18px;height:18px;"
                                     class="abcRioButtonSvgImageWithFallback abcRioButtonIconImage abcRioButtonIconImage18">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px"
                                         height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg">
                                        <g>
                                            <path fill="#EA4335"
                                                  d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path
                                                    fill="#4285F4"
                                                    d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path
                                                    fill="#FBBC05"
                                                    d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path
                                                    fill="#34A853"
                                                    d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path
                                                    fill="none" d="M0 0h48v48H0z"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <span class="abcRioButtonContents">
                                <span id="not_signed_inyx5syaq6qblq">{{__('با گوگل وارد شوید')}}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="registerFSection">
            <div class="row">
                <div class="col-md-6 loginInputDiv">
                    <input type="text" id="emailPhone_register" class="loginInput" placeholder="{{__('تلفن همراه یا ایمیل')}}">
                </div>
                <div class="col-md-6 loginInputDiv">
                    <input type="password" id="password_register" class="loginInput" placeholder="{{__('رمز عبور')}}">
                </div>
            </div>
            <div class="row pcMarginTop10">
                <div class="col-sm-6 loginInputDiv nameRegisterDiv">
                    <input type="text" id="username_register" class="loginInput" placeholder="{{__('نام')}}">
                    <div class="bottomLoginText">
                        {{__('دوستانتان در سایت شما را با این نام خواهند شناخت.')}}
                    </div>
                </div>
                <div class="col-sm-6 loginInputDiv buttonRegisterDiv">
                    <button class="loginRegisterButton" onclick="firstRegisterStep()">{{__('ثبت نام')}}</button>
                    <div class="registerErr" style="color: red;"></div>
                </div>
            </div>
        </div>

        <div class="loginFSection">
            <div class="headerTextF">{{__('اگر در کوچیتا عضو هستید؛')}}
            </div>
            <div class="row">
                <div class="col-sm-5 loginInputDiv">
                    <input type="text" id="username_main" class="loginInput" placeholder="{{__('تلفن همراه، ایمیل یا نام')}}">
                </div>
                <div class="col-sm-5 loginInputDiv">
                    <input type="password" id="password_main" class="loginInput" placeholder="{{__('رمز عبور')}}">
                    <div class="bottomLoginText forgetPassBut" onclick="openRegisterSection('ForgetPassword')">
                        {{__('رمز عبور خود را فراموش کردید؟')}}
                    </div>
                </div>
                <div class="col-sm-2 loginInputDiv" style="margin-top: 0px">
                    <button class="loginRegisterButton" style="background: var(--koochita-blue); width: 90px;" onclick="login($('#username_main').val(), $('#password_main').val())">{{__('ورود')}}</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 loginErr" style="color: red"></div>
            </div>
        </div>
    </div>

    <div id="registerDiv" class="loginPopUpContent hidden" style="justify-content: space-between; flex-direction: row; padding: 15px;">
        <div class="iconFamily iconClose closeLoginPopup" onclick="closeLoginPopup()"></div>

        <div class="registerRightSection">

            <div id="registerMainLogo">
                <img class="loginMainLogo" src="{{URL::asset('images/mainPics/mainIcon.svg')}}">
            </div>

            <div>
                <span id="EnterEmail-loginPopUp" onkeyup="if(event.keyCode == 13) emailRegister()" class="hidden">
                    <div class="mainContentInfos">
                        <div class="header_text font-size-14Imp">{{__('عضو شوید:')}}</div>
                        <div>
                            <div class="loginPopUpLable">{{__('آدرس ایمیل خود را وارد کنید')}}</div>
                            <input type="email" id="emailRegisterInput" class="loginInputTemp" style="width: 100%" maxlength="40" required autofocus>
                        </div>
                        <div class="pd-tp-8">
                            <p id="loginErrEmail"></p>
                            <div style="display: flex;">
                                <button type="button" onclick="emailRegister()" class="loginSubBtn btn btn-info active" style="margin-left: 10px">{{__('ثبت')}}</button>
                                <button type="button" onclick="Return('EnterEmail-loginPopUp')" class="loginReturnBtn btn btn-default">{{__('بازگشت')}}</button>
                            </div>
                        </div>
                    </div>
                </span>

                <span id="sendVerifiedPhoneCodeSection" class="hidden" onkeyup="if(event.keyCode == 13) registerWithPhone()">
                    <div class="col-xs-12 rtl mainContentInfos">
                        <div>{{__('لطفا کد اعتبار سنجی را وارد نمایید:')}}</div>
                        <div style="margin-top: 10px">
                            <span class="header_text font-size-12Imp">{{__('این کد به گوشی شما ارسال گردیده است.')}}</span>
                            <div>
                                <div class="loginPopUpLable"> {{__('کد اعتبار سنجی')}} </div>
                                <input class="loginInputTemp" type="text" maxlength="40" id="activationCode" required autofocus>
                                <p id="loginErrActivationCode" class="loginErrActivationCode"></p>
                            </div>
                            <div id="reminderTimePane" class="reminderTimeDiv" style="font-size: 13px; margin-bottom: 0px">
                                <span>{{__('زمان باقی مانده برای ارسال مجدد کد اعتبار سنجی شما :')}}</span>
                                <span id="reminderTime" class="reminderTime"></span>
                            </div>
                            <button onclick="resendActivationCode('register', this)" id="resendActivationCode" class="btn comButLogin resendActivationCode hidden"> {{__('ارسال مجدد کد اعتبار سنجی')}} </button>
                        </div>
                        <hr>
                        <div>
                            <span style="font-size: 12px">{{__('در صورتی که دوستانتان شما را معرفی کرده اند، کد معرف خود را در کادر زیر وارد کنید.')}}</span>
                            <div class="loginRowsPopup loginRowsPopupInline">
                                <span  class="loginInputLabel">{{__('کد معرف')}}</span>
                                <input type="text" id="invitationCode" class="loginInputTemp" maxlength="6">
                            </div>
                        </div>
                        <div style="display: flex; margin: 10px 0px;">
                            <input id='checked2' onchange='checkedCheckBox(this)' type='checkbox' value='-1' style="margin-left: 5px;">
                            <label class='labelForCheckBox' for='checked2'>
                                <span></span>&nbsp;
                            </label>
                            <div style="margin-right: 5px"> {{__('شرایط استفاده و')}}
                                <a target="_blank" href="{{route('policies')}}" style="color: blue;">{{__('قوانین سایت')}}</a>
                                {{__('را مطالعه کرده و با آن موافقم.')}}
                            </div>
                        </div>
                        <div class="phoneRegisterErr" style="color: red;"></div>
                        <div class="pd-tp-8">
                            <button type="button" onclick="registerWithPhone()" class="loginSubBtn btn btn-info active submitAndFinishBtn" disabled>{{__('ثبت نام')}}</button>
                            <button type="button" onclick="Return('sendVerifiedPhoneCodeSection')" class="loginReturnBtn btn btn-default">{{__('بازگشت')}}</button>
                        </div>
                    </div>
                </span>

                {{--Enter Username in login PopUp--}}
                <span id="EnterUsername-loginPopUp" class="hidden">
                    <div class="col-xs-12 rtl mainContentInfos">
                        <div class="header_text font-size-14Imp font-weight-700">{{__('قدم آخر!')}}</div>
                            <div>
                                <span style="font-size: 12px">{{__('در صورتی که دوستانتان شما را معرفی کرده اند، کد معرف خود را در کادر زیر وارد کنید.')}}</span>
                                <div class="loginRowsPopup loginRowsPopupInline" style="margin-top: 5px">
                                    <span  class="loginInputLabel">{{__('کد معرف')}}</span>
                                    <input type="text" id="invitationCode" class="loginInputTemp" maxlength="6">
                                </div>
                            </div>
                            <div style="display: flex; margin: 10px 0px;">
                                <input id='checked1' onchange='checkedCheckBox(this)' type='checkbox' value='-1' style="margin-left: 5px;">
                                <label class='labelForCheckBox' for='checked1'>
                                    <span></span>&nbsp;
                                </label>
                                <div style="margin-right: 5px"> {{__('شرایط استفاده و')}}
                                    <a target="_blank" href="{{route('policies')}}" style="color: blue;">{{__('قوانین سایت')}}</a>
                                    {{__('را مطالعه کرده و با آن موافقم.')}}
                                </div>
                            </div>
                            <div>
                                <script async src='https://www.google.com/recaptcha/api.js?hl=fa'></script>
                                <div class="g-recaptcha" data-sitekey="{{config('app.GOOGLE_RECAPTCHA_SITEKEY')}}"></div>
                            </div>
                            <button type="button" onclick="checkRecaptcha()" class="loginSubBtn btn btn-info active submitAndFinishBtn" disabled>{{__('ثبت')}}</button>
                            <p id="loginErrUserName"></p>
                    </div>
                </span>

                {{--Forget Password in login PopUp--}}
                <span id="ForgetPassword" class="hidden">
                    <div class="col-xs-12 mainContentInfos">
                        <div style="margin-bottom: 10px">{{__('برای بازیابی رمزعبور تان از کدام طریق اقدام میکنید:')}}</div>
                        <div>
                            <button class="btn showDetailsBtn" onclick="showForgatenPassInput('Email_ForgetPass')">
                                <div class="emailLogo"></div>
                                <span class="float-right">{{__('ایمیل')}}</span>
                            </button>
                            <button class="btn showDetailsBtn" onclick="showForgatenPassInput('Phone_ForgetPass')">
                                <div class="phoneLogo"></div>
                                <span class="float-right">{{__('تلفن همراه')}}</span>
                            </button>
                        </div>
                        <div class="pd-tp-8">
                            <button type="button" onclick="Return('ForgetPassword')" class="returnBtnForgetPass btn btn-default">{{__('بازگشت')}}</button>
                            <p id="loginErr"></p>
                        </div>
                    </div>
                </span>

                {{--Enter Email for ForgetPass in login PopUp--}}
                <span id="Email_ForgetPass" class="hidden">
                    <div class="col-xs-12 rtl mainContentInfos">
                        <div>
                            <span class="pd-tp-8"> {{__('ایمیل')}} </span>
                            <input class="loginInputTemp" type="email" id="forget_email" maxlength="40" required autofocus>
                        </div>
                        <div id="reminderTimeEmailForgetPassDiv" class="reminderTimeDiv hidden" style="font-size: 13px; margin-bottom: 0px">
                            <span>{{__('زمان باقی مانده برای ارسال مجدد لینک بازیابی رمز عبور :')}}</span>
                            <span id="reminderTimeEmailForgetPass" class="reminderTime"></span>
                        </div>
                        <button onclick="retrievePasByEmail()" id="resendForgetPassEmailButton" class="btn comButLogin resendActivationCode" style="margin-top: 15px;"> {{__('ارسال لینک بازیابی')}} </button>
                        <div class="pd-tp-8">
                            <button type="button" onclick="Return('Email_ForgetPass')" class="loginReturnBtn btn btn-default">{{__('بازگشت')}}</button>
                            <p id="loginErrResetPasByEmail"></p>
                        </div>
                    </div>
                </span>

                {{--Enter Phone for ForgetPass in login PopUp--}}
                <span id="Phone_ForgetPass" class="hidden">
                    <div class="col-xs-12 mainContentInfos">
                        <div>
                            <span class="pd-tp-8"> {{__('شماره موبایل خود را وارد نمایید')}} </span>
                            <input class="loginInputTemp" placeholder="09xxxxxxxxx" type="tel" id="phoneForgetPass" maxlength="40" required autofocus>
                            <div class="pd-tp-8">
                                <button type="button" onclick="sendForgetPassPhone()" class="loginSubBtn btn btn-info active">{{__('ثبت')}}</button>
                                <button type="button" onclick="Return('Phone_ForgetPass')" class="loginReturnBtn btn btn-default">{{__('بازگشت')}}</button>
                                <p id="loginErrResetPasByPhone"></p>
                            </div>
                        </div>
                    </div>
                </span>

                <span id="PhoneCodePasswordForget" class="hidden">
                    <div class="col-xs-12 rtl mainContentInfos">
                        <div>{{__('لطفا کد اعتبار سنجی را وارد نمایید:')}}</div>
                        <div style="margin-top: 10px">
                            <span class="header_text font-size-12Imp">{{__('این کد به گوشی شما ارسال گردیده است.')}}</span>
                            <div>
                                <div class="loginPopUpLable"> {{__('کد اعتبار سنجی')}} </div>
                                <input class="loginInputTemp" type="text" maxlength="40" id="activationCodeForgetPass" required autofocus>
                                <p id="loginErrActivationCodeForgetPass" class="loginErrActivationCode" style="color: #963019"></p>
                            </div>
                            <div id="reminderTimePaneForgetPass" class="reminderTimeDiv" style="font-size: 13px; margin-bottom: 0px">
                                <span>  {{__('زمان باقی مانده برای ارسال مجدد کد اعتبار سنجی شما :')}}</span>
                                <span id="reminderTimeForgetPass" class="reminderTime"></span>
                            </div>
                            <button onclick="resendActivationCode('forget', this)" id="resendActivationCodeForgetPass" class="btn comButLogin resendActivationCode hidden"> {{__('ارسال مجدد کد اعتبار سنجی')}} </button>
                        </div>
                        <div class="pd-tp-8">
                            <button type="button" onclick="checkValidateForgetPass()" class="loginSubBtn btn btn-info active">{{__('ثبت')}}</button>
                            <button type="button" onclick="Return('PhoneCodePasswordForget')" class="loginReturnBtn btn btn-default">{{__('بازگشت')}}</button>
                        </div>
                    </div>
                </span>

                <span id="setNewPassword" class="hidden">
                    <div class="col-xs-12 rtl mainContentInfos">
                        <div>{{__('رمز عبور جدید خود را وارد نمایید:')}}</div>
                        <div style="margin-top: 10px">
                            <div>
                                <div class="loginPopUpLable"> {{__('رمز عبور جدید')}} </div>
                                <input class="loginInputTemp" type="password" id="newPassword" required autofocus>
{{--                                <p id="loginErrActivationCodeForgetPass" class="loginErrActivationCode" style="color: #963019"></p>--}}
                            </div>
                        </div>
                        <div class="pd-tp-8">
                            <button type="button" onclick="submitNewPassword()" class="loginSubBtn btn btn-info active">{{__('ثبت')}}</button>
                        </div>
                    </div>
                </span>

            </div>
        </div>

        <div class="registerLeftSection">
            <img src="{{URL::asset('images/mainPics/bck.jpg')}}" class="resizeImgClass" style="width: 100%;" onload="resizeFitImg(this)">
        </div>

    </div>
</div>

<script>

    let phoneCodeRegister = null;
    var selectedUrl = "";
    var back = "";
    var email = "";
    var pas = "";
    var username = "";
    var phoneNum = "";
    var reminderTime = 0;
    var reminderTime2 = 0;
    var mainLoginDir = '{{route('login2')}}';
    var checkEmailDir = '{{route('checkEmail')}}';
    var checkUserNameDir = '{{route('checkUserName')}}';
    var checkReCaptchaDir = '{{route('checkReCaptcha')}}';
    var retrievePasByPhoneDir = '{{route('retrievePasByPhone')}}';
    var retrievePasByEmailDir = '{{route('retrievePasByEmail')}}';
    var resendActivationCodeForgetDir = '{{route('resendActivationCodeForget')}}';

    function showLoginEmail() {
        $('#loginPopUp').addClass('hidden');
        $('#EnterEmail-loginPopUp').removeClass('hidden');
    }

    function firstRegisterStep(){
        let name = $('#username_register').val();
        let emailPhone = $('#emailPhone_register').val();
        let password = $('#password_register').val();
        $('.registerErr').html('');

        if(name.trim().length > 0 && emailPhone.trim().length > 0 && password.trim().length > 0){

            let kind = 'email';
            if(!emailPhone.includes('@')){
                let phone = fixNumbers(emailPhone);
                if(!(phone.trim().length == 11 && phone[0] == 0 && phone[1] == 9)){
                    $('.registerErr').html('شماره تماس خود را به درستی وارد نمایید.');
                    return;
                }
                kind = 'phone';
            }

            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('checkRegisterData')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    name: name,
                    emailPhone: emailPhone,
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response.status == 'ok'){
                            if(response.nameErr || response.phoneErr || response.emailErr) {
                                closeLoading();

                                if (response.nameErr)
                                    $('.registerErr').append('<div>نام وارد شده تکراری می باشد.</div>');
                                if (response.phoneErr)
                                    $('.registerErr').append('<div>شماره تماس وارد شده تکراری می باشد.</div>');
                                if (response.emailErr)
                                    $('.registerErr').append('<div>ایمیل وارد شده تکراری می باشد.</div>');
                            }
                            else{
                                if(kind == 'phone')
                                    checkInputPhoneRegister();
                                else
                                    openUserRegisterationPage();
                            }
                        }
                    }
                    catch (e) {
                        closeLoading();
                        showSuccessNotifi('در فرایند ثبت نام مشکلی پیش امده لطفا دوباره تلاش نمایید.', 'left', 'red');
                    }
                },
                error: function(err){
                    closeLoading();
                    showSuccessNotifi('در فرایند ثبت نام مشکلی پیش امده لطفا دوباره تلاش نمایید.', 'left', 'red');
                }
            })

        }
        else
            $('.registerErr').html('پر کردن تمامی فیلد ها الزامی است.');

    }

    function openRegisterSection(_kind){
        $('#registerDiv').removeClass('hidden');
        $('#loginPopUp').addClass('hidden');

        if(_kind == 'email')
            $('#EnterEmail-loginPopUp').removeClass('hidden');
        else if(_kind == 'ForgetPassword')
            $('#ForgetPassword').removeClass('hidden');
        else
            $('#EnterPhone-loginPopUp').removeClass('hidden');
    }

    function Return(_id) {
        $('#' + _id).addClass('hidden');
        switch (_id) {
            case 'EnterEmail-loginPopUp':
            case 'EnterPhone-loginPopUp':
            case 'ForgetPassword':
                $('#registerDiv').addClass('hidden');
                $('#loginPopUp').removeClass('hidden');
                break;
            case 'sendVerifiedPhoneCodeSection':
                $('#loginPopUp').removeClass('hidden');
                $('#registerDiv').addClass('hidden');
                $('#sendVerifiedPhoneCodeSection').addClass('hidden');
                break;
            case 'Email_ForgetPass':
            case 'Phone_ForgetPass':
                $('#ForgetPassword').removeClass('hidden');
                break;
            case 'PhoneCodePasswordForget':
                $('#Phone_ForgetPass').removeClass('hidden');
                break;
        }
    }

    function closeLoginPopup(){
        $('#mainLoginPopUp').addClass('hidden');
    }

    function closeRegister(){
        $('#registerDiv').addClass('hidden');
        $('#loginPopUp').removeClass('hidden');
    }

    function emailRegister(){
        let email = $('#emailRegisterInput').val();
        if(email.trim().length > 0 && validateEmail(email)){
            openLoading();
            $.ajax({
                type: 'post',
                url: checkEmailDir,
                data:{
                    _token: '{{csrf_token()}}',
                    email: email
                },
                success: function(response){
                    closeLoading();
                    if(response == 'ok')
                        openUserRegisterationPage();
                    else if(response == 'nok')
                        $('#loginErrEmail').empty().append('{{__('ایمیل وارد شده در سامانه موجود است')}}');
                    else
                        $('#loginErrEmail').empty().append('{{__('در فرایند ثبت نام مشکلی پیش امده لطفا دوباره تلاش کنید')}}');
                },
                error: function(err){
                    console.log(err);
                    closeLoading();
                    $('#loginErrEmail').empty().append('{{__('در فرایند ثبت نام مشکلی پیش امده لطفا دوباره تلاش کنید')}}');
                }
            })
        }
        else
            $('#loginErrEmail').empty().append('{{__('ایمیل خود را به درستی وارد کنید.')}}');
    }

    function checkInputPhoneRegister() {
        let phone = $('#emailPhone_register').val();
        phone = fixNumbers(phone);
        $.ajax({
            type: 'post',
            url: '{{route('checkPhoneNum')}}',
            data: {
                phoneNum: phone
            },
            success: function (response) {
                closeLoading();
                if(phoneCodeRegister != null)
                    clearTimeout(phoneCodeRegister);

                response = JSON.parse(response);

                if (response.status == "ok") {
                    phoneNum = phone;
                    reminderTime = response.reminder;

                    if (reminderTime > 0) {
                        $(".reminderTimeDiv").removeClass('hidden');
                        $(".resendActivationCode").addClass('hidden');
                        phoneCodeRegister = setInterval("decreaseTime()", 1000);
                    }
                    else {
                        $(".reminderTimeDiv").addClass('hidden');
                        $(".resendActivationCode").removeClass('hidden');
                    }

                    $("#activationCode").val("");
                    $('#loginPopUp').addClass('hidden');
                    $('#registerDiv').removeClass('hidden');
                    $('#sendVerifiedPhoneCodeSection').removeClass('hidden');
                }
                else if (response.status == "nok")
                    $("#loginErrPhonePass1").empty().append('{{__('شماره شما پیش از این در سامانه ثبت گردیده است.')}}');
                else if (response.status == "nok3")
                    $("#loginErrPhonePass1").empty().append('{{__('اشکالی در ارسال پیام رخ داده است')}}');
                else
                    $("#loginErrPhonePass1").empty().append('{{__('کد اعتبار سنجی برای شما ارسال شده است. برای ارسال مجدد کد منتظر بمانید')}}');
            },
            error: function(err){
                closeLoading();
                console.log(err);
            }
        });
    }

    function resendActivationCode(_kind) {
        let phoneNum;
        if(_kind == 'forget')
            phoneNum = $('#phoneForgetPass').val();
        else
            phoneNum = $('#emailPhone_register').val();

        phoneNum = fixNumbers(phoneNum);

        $.ajax({
            type: 'post',
            url: '{{route('resendActivationCode')}}',
            data: {
                phoneNum: phoneNum
            },
            success: function (response) {

                response = JSON.parse(response);
                if(phoneCodeRegister != null)
                    clearTimeout(phoneCodeRegister);

                reminderTime = response.reminder;

                if (response.status == "ok") {
                    if (reminderTime > 0) {
                        $(".reminderTimeDiv").removeClass('hidden');
                        $(".resendActivationCode").addClass('hidden');
                        phoneCodeRegister = setInterval("decreaseTime()", 1000);
                    }
                    else {
                        $(".reminderTimeDiv").addClass('hidden');
                        $(".resendActivationCode").removeClass('hidden');
                    }
                } else {
                    $(".reminderTimeDiv").addClass('hidden');
                    $(".resendActivationCode").removeClass('hidden');
                    phoneCodeRegister = setInterval("decreaseTime()", 1000);
                }
            }
        })

    }

    function registerWithPhone() {
        let username = $('#username_register').val();
        let phone = $('#emailPhone_register').val();
        let password = $('#password_register').val();
        let actCode = $('#activationCode').val();
        let inviteCode = $('#invitationCode').val();

        phone = fixNumbers(phone);
        actCode = fixNumbers(actCode);
        inviteCode = fixNumbers(inviteCode);
        $(".phoneRegisterErr").html('');

        if(actCode.trim().length > 2) {
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('registerAndLogin')}}',
                data: {
                    username: username,
                    password: password,
                    phone: phone,
                    actCode: actCode,
                    invitationCode: inviteCode
                },
                success: function (response) {
                    if (response == "ok")
                        login(username, password);
                    else if (response == "nok1") {
                        closeLoading();
                        $('.phoneRegisterErr').append('<span>نام کاربری وارد شده در سامانه موجود است</span>');
                    } else if (response == 'nok5') {
                        closeLoading();
                        $('.phoneRegisterErr').append('<span>کد وارد شده نامعتبر می باشد.</span>');
                    } else {
                        closeLoading();
                        $('.phoneRegisterErr').append("<span>{{__('کد معرف وارد شده نامعتبر است')}}</span>");
                    }
                },
                error: function (err) {
                    console.log(err);
                    closeLoading();
                    showSuccessNotifi('{{__('در فرایند ثبت نام مشکلی پیش امده لطفا دوباره تلاش کنید.')}}', 'left', 'red');
                }
            });
        }

    }

    function checkValidationPhoneCode(_phoneId, _codeId, _callback = ''){
        let phoneNum = $('#' + _phoneId).val();
        let code = $('#' + _codeId).val();

        phoneNum = fixNumbers(phoneNum);
        code = fixNumbers(code);

        if(code.trim().length > 0) {
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('checkActivationCode')}}',
                data: {
                    'phoneNum': phoneNum,
                    'activationCode': code
                },
                success: function (response) {
                    closeLoading();
                    if (response == "ok"){
                        if(typeof _callback === 'function')
                            _callback();
                    }
                    else
                        $(".loginErrActivationCode").empty().append('{{__('کد وارد شده معتبر نمی باشد')}}');
                },
                error: function(err){
                    closeLoading();
                    console.log(err);
                    showSuccessNotifi(' {{__('در فرایند ثبت نام مشکلی پیش امده لطفا دوباره تلاش کنید.')}}', 'left', 'red');
                }
            });
        }
    }

    function decreaseTime() {
        $(".reminderTime").text((reminderTime % 60) + " : " + Math.floor(reminderTime / 60));

        if (reminderTime > 0)
            reminderTime--;
        else {
            clearTimeout(phoneCodeRegister);
            $(".reminderTimeDiv").addClass('hidden');
            $(".resendActivationCode").removeClass('hidden');
        }
    }

    function checkRecaptcha() {
        let username = $('#username_register').val();
        let password = $('#password_register').val();

        if(username.trim().length > 0 && password.trim().length > 0) {
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('checkReCaptcha')}}',
                data: {
                    captcha: grecaptcha.getResponse()
                },
                success: function (response) {
                    if (response == "ok")
                        registerWithEmail();
                    else {
                        closeLoading();
                        $("#loginErrUserName").empty().append('{{__('لطفا ربات نبودن خود را ثابت کنید')}}');
                    }
                }
            });
        }
        else{
            closeLoading();
            $("#loginErrUserName").empty().append('{{__('لطفا نام کاربری و رمز عبور خود را مشخص کنید')}}');
        }
    }

    function registerWithEmail() {
        let username = $('#username_register').val();
        let password = $('#password_register').val();
        let email = $('#emailPhone_register').val();
        let inviteCode = $("#invitationCode").val();

        $.ajax({
                type: 'post',
                url: '{{route('registerAndLogin')}}',
                data: {
                    username: username,
                    password: password,
                    email: email,
                    invitationCode: inviteCode
                },
                success: function (response) {
                    if (response == "ok")
                        login(username, password);
                    else if (response == "nok1") {
                        closeLoading();
                        $("#loginErrUserName").empty().append('{{__('نام کاربری وارد شده در سامانه موجود است')}}');
                    }
                    else {
                        closeLoading();
                        $("#loginErrUserName").empty().append('{{__('کد معرف وارد شده نامعتبر است')}}');
                    }
                },
                error: function (err) {
                    console.log(err);
                    closeLoading();
                    showSuccessNotifi('{{__('در فرایند ثبت نام مشکلی پیش امده لطفا دوباره تلاش کنید.')}}', 'left', 'red');
                }
            });
    }

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function checkedCheckBox(_element) {

        if ($(_element).is(":checked")) {
            $(".submitAndFinishBtn").removeAttr('disabled');
        } else {
            $(".submitAndFinishBtn").attr('disabled', 'disabled');
        }
    }

    function openUserRegisterationPage(){
        $('#loginPopUp').addClass('hidden');
        $('#registerDiv').removeClass('hidden');
        $('#EnterUsername-loginPopUp').removeClass('hidden');
        closeLoading();
    }

    function showForgatenPassInput(_id){
        $('#'+_id).removeClass('hidden');
        $('#ForgetPassword').addClass('hidden');
    }

    function login(username, password) {
        if (username.trim().length > 0 && password.trim().length > 0) {
            openLoading();
            $.ajax({
                type: 'post',
                url: mainLoginDir,
                data: {
                    _token: '{{csrf_token()}}',
                    username: username,
                    password: password
                },
                success: function (response) {
                    if (response == "ok") {
                        $('#form_userName').val(username);
                        $('#form_pass').val(password);
                        $('#redirectUrl').val(selectedUrl);
                        $('#second_login').submit();
                    }
                    else if (response == "nok2") {
                        closeLoading();
                        $(".loginErr").empty().append('{{__('حساب کاربری شما غیر فعال شده است')}}');
                    }
                    else {
                        closeLoading();
                        $(".loginErr").empty().append('{{__('نام کاربری و یا رمز عبور اشتباه وارد شده است')}}');
                    }
                },
                error: function (xhr, status, error) {
                    closeLoading();
                    console.log(xhr.responseText);
                    if (xhr.responseText == "Too Many Attempts.")
                        $(".loginErr").empty().append('{{__('تعداد درخواست های شما بیش از حد مجاز است. لطفا تا 5 دقیقه دیگر تلاش نفرمایید')}}');
                }
            });
        }
    }

    function sendForgetPassPhone(){
        let phoneNum = $('#phoneForgetPass').val();
        phoneNum = fixNumbers(phoneNum);

        if(phoneNum.trim().length == 11 && phoneNum[0] == 0 && phoneNum[1] == 9){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('retrievePasByPhone')}}',
                data: {
                    'phone': phoneNum
                },
                success: function (response) {
                    closeLoading();

                    if(phoneCodeRegister != null)
                        clearTimeout(phoneCodeRegister);

                    response = JSON.parse(response);
                    if (response.status == "ok") {
                        reminderTime = response.reminder;
                        if (reminderTime > 0) {
                            $(".reminderTimeDiv").removeClass('hidden');
                            $(".resendActivationCode").addClass('hidden');
                            phoneCodeRegister = setInterval("decreaseTime()", 1000);
                        }
                        else {
                            $(".reminderTimeDiv").addClass('hidden');
                            $(".resendActivationCode").removeClass('hidden');
                        }
                        $("#activationCodeForgetPass").val("");
                        $('#Phone_ForgetPass').addClass('hidden');
                        $('#PhoneCodePasswordForget').removeClass('hidden');
                    }
                    else if (response.status == "nok")
                        $("#loginErrActivationCodeForgetPass").empty().append('{{__('شماره شما پیش از این در سامانه ثبت گردیده است.')}}');
                    else if (response.status == "nok3")
                        $("#loginErrActivationCodeForgetPass").empty().append('{{__('اشکالی در ارسال پیام رخ داده است')}}');
                    else
                        $("#loginErrActivationCodeForgetPass").empty().append('{{__('کد اعتبار سنجی برای شما ارسال شده است. برای ارسال مجدد کد باید 5 دقیقه منتظر بمانید')}}');
                },
                error: function(err){
                    closeLoading();
                    console.log(err);
                    showSuccessNotifi('{{__('در فرایند بازبابی رمز عبور مشکلی پیش امده لطفا دوباره تلاش کنید.')}}', 'left', 'red');
                }
            });
        }
        else
            $("#loginErrActivationCodeForgetPass").empty().append('{{__('شماره موبایل خود را به درستی وارد نمایید.')}}');
    }

    function checkValidateForgetPass(){
        checkValidationPhoneCode('phoneForgetPass', 'activationCodeForgetPass', function(){
            $('#setNewPassword').removeClass('hidden');
            $('#PhoneCodePasswordForget').addClass('hidden');
        })
    }

    function submitNewPassword(){
        let newPass = $('#newPassword').val();
        let phone = $('#phoneForgetPass').val();
        let code = $('#activationCodeForgetPass').val();
        phone = fixNumbers(phone);
        if(newPass.trim().length > 0){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('user.setNewPassword')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    pass: newPass,
                    phone: phone,
                    code: code
                },
                success: function(response){
                    closeLoading();
                    if(response == 'ok'){
                        showSuccessNotifi('{{__('رمز شما با موفقیت تغییر یافت')}}', 'left', 'var(--koochita-blue)');

                        $("#setNewPassword").addClass('hidden');
                        closeRegister();
                    }
                    else if(response == 'nok5'){
                        $('#setNewPassword').addClass('hidden');
                        $('#Phone_ForgetPass').removeClass('hidden');
                        showSuccessNotifi('{{__('لطفا دوباره شماره تماس خود را تایید کنید')}}', 'left', 'red');
                        closeLoading();
                    }
                },
                error: function(err){
                    closeLoading();
                    console.log(err);
                    showSuccessNotifi('{{__('در هنگام تغییر رمز مشکلی پیش امده لظفا دوباره تلاش نمایید')}}', 'left', 'red');
                }
            })
        }
    }

    function showLoginPrompt(url = '{{Request::url()}}') {
        selectedUrl = url;
        $("#username_main").val("");
        $("#password_main").val("");
        $('#mainLoginPopUp').removeClass('hidden');
    }

    function retrievePasByEmail(){
        let email = $('#forget_email').val();
        if(email.trim().length > 0){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('retrievePasByEmail')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    email: email
                },
                success:function(response){
                    closeLoading();
                    try{
                        response = JSON.parse(response);
                        if(response.status == 'ok'){
                            showSuccessNotifi('{{__('لینک بازیابی به لینک شما ارسال شد')}}', 'left', 'red');
                            $('#reminderTimeEmailForgetPassDiv').removeClass('hidden');
                            $('#resendForgetPassEmailButton').addClass('hidden');
                            reminderTime = response.remainder;
                            phoneCodeRegister = setInterval("decreaseTime()", 1000);
                        }
                        else if(response.status == 'nok')
                            showSuccessNotifi('{{__('در بازیبای رمز عبور مشکلی پیش امده لطفا دوباره تلاش نمایید')}}', 'left', 'red');
                        else if(response.status == 'nok1')
                            $('#loginErrResetPasByEmail').text('{{__('کاربری با این ایمیل در سامانه ثبت نشده است')}}');
                        else if(response.status == 'nok2')
                            showSuccessNotifi('{{__('در بازیبای رمز عبور مشکلی پیش امده لطفا دوباره تلاش نمایید')}}', 'left', 'red');
                        else if(response.status == 'nok3') {
                            reminderTime = response.remainder;
                            phoneCodeRegister = setInterval("decreaseTime()", 1000);
                            $('#reminderTimeEmailForgetPassDiv').removeClass('hidden');
                            $('#resendForgetPassEmailButton').addClass('hidden');
                        }

                    }
                    catch (e) {
                        console.log(e)
                        showSuccessNotifi('{{__('در بازیبای رمز عبور مشکلی پیش امده لطفا دوباره تلاش نمایید')}}', 'left', 'red');
                    }
                },
                error: function(err){
                    console.log(err);
                    closeLoading();
                    showSuccessNotifi('{{__('در بازیبای رمز عبور مشکلی پیش امده لطفا دوباره تلاش نمایید')}}', 'left', 'red');
                }
            })
        }
    }

    function fixNumbers(str){
        let persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        let arabicNumbers  = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g];
        if(typeof str === 'string') {
            for (var i = 0; i < 10; i++)
                str = str.replace(persianNumbers[i], i).replace(arabicNumbers[i], i);
        }
        return str;
    };

    $(document).ready(function () {
        $(".login-button").click(function () {
            showLoginPrompt('{{Request::url()}}')
        })
    });
</script>
