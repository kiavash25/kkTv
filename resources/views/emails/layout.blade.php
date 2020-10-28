<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 " />
    <meta charset="UTF-8">
    <title>KoochitaTV</title>
    <style>
        *{
            font-family: Tahoma,Geneva,sans-serif;
            text-align: right;
            direction: rtl;
        }
        .container{
            max-width: 600px;
            width: 100%;
            margin: 0px auto;
        }
        .row{
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            position: relative;
            max-width: 600px;
            margin: 0;
        }
        .headerRow{
            position: relative;
            height: 80px
        }
        .header{
            margin-bottom: 15px;
            width: 100%;
            direction: rtl;
            text-align: right;
            font-size: 22px;
            background: white;
            box-shadow: 0 0 20px 0px grey;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            direction: ltr;
        }
        .headerLogo{
            height: 45px;
            margin-top: 13px;
            width: auto;
            margin-right: 4px;
        }
        .headerText{
            padding: 10px;
            margin: auto 0px
        }
        .headerPicDiv{
            height: 100%;
            width: 100px;
            background: var(--koochita-light-green);
            margin-left: 20px;
            margin-right: auto;
            z-index: 9;
        }
        .bodyRow{
            position: relative;
        }
        .body{
            padding: 15px 0px;
            width: 100%;
            direction: rtl;
            text-align: right;
            font-size: 22px;
            background: white;
            box-shadow: 0 0 20px 0px grey;
        }
        .textBot{
            padding: 20px;
            font-size: 13px;
            margin-top: 20px;
        }
        .footer{
            width: 100%;
            margin-top: 20px;
            background: var(--koochita-yellow);
            display: block;
            padding-bottom: 20px;
        }
        .footerRight{
            width: 60%;
            text-align: right;
            font-size: 20px;
        }
        .footerLeft{
            width: 40%;
            padding: 20px 0px;
        }
        .footerText{
            padding-right: 17px;
            margin: 15px 0px;
        }
        .footerSocial{
            width: 40px;
            height: 39px;
            margin-right: auto;
            margin-left: auto;
        }
        .footerSocialDiv{
            display: flex;
            margin-top: 30px;
        }
        .socialIcon{
            width: 100%;
            height: 100%;
            background-size: cover;
        }
        .footerLogo{
            width: 250px;
        }
        @media (max-width: 600px){
            .footerLogo{
                width: 150px;
            }
            .headerText{
                font-size: 18px;
            }
        }
    </style>

    @yield('head')

</head>
<body style="background: #eaeaea; width: 100%; direction: ltr">
    <div class="container">
        <div class="row headerRow">
            <div class="header">

                <div class="headerPicDiv">
                    <img src="https://koochita.com/images/icons/icon.png" alt="koochita" title="koochita" class="headerLogo">
                </div>
                <div class="headerText">
                    {{$header}}
                </div>
            </div>
        </div>

        <div class="row bodyRow">
            <div class="body">
                <table style="background: rgb(233, 251, 255); padding: 15px; width: 100%">
                    <tr>
                        <td style="font-weight: bold">
                            سلام
                        </td>
                    </tr>
                    <tr>
                        <td> {{$userName}} عزیز </td>
                    </tr>
                </table>

                @yield('body')

                <div class="textBot">
                    لطفا به این ایمیل پاسخ ندهید . این ایمیل صرفا برای اطلاع رسانی می باشد و قابلیت دریافت پاسخ را ندارد. در صورتی که به پشتیبانی نیاز دارید
                    <span>
                        <a href="https://koochita.com/policies">
                            از اینجا
                        </a>
                    </span>
                    اقدام نمایید.
                </div>
            </div>
        </div>

        <div class="row footer">

            <div style="width: 100%; text-align: center">
                <img src="https://koochita.com/images/icons/mainLogo.png" class="footerLogo">
            </div>
            <div class="footerText">
                فرستاده شده توسط:
                <a href="https://koochita.com/main" style="color: black; text-decoration: none;">Koochita</a>
            </div>
            <div class="footerText">
                تلفن پشتیبانی:
                <a href="tel:02188195360" style="color: black; text-decoration: none;">02188195360</a>
                -
                <a href="tel:09120239315" style="color: black; text-decoration: none;">09120239315</a>
                <span style="font-size: 12px">
                    جهت ارتباط در شبکه های مجازی
                </span>
            </div>
            <div class="footerSocialDiv">
                <div class="footerSocial">
                    <a href="#" class="socialIcon">
                        <img src="https://koochita.com/images/icons/linkedin.png" style="width: 100%; height: 100%">
                    </a>
                    <div class="socialName"></div>
                </div>
                <div class="footerSocial">
                    <a href="#" class="socialIcon">
                        <img src="https://koochita.com/images/icons/facebook.png" style="width: 100%; height: 100%">
                    </a>
                    <div class="socialName"></div>
                </div>
                <div class="footerSocial">
                    <a href="#" class="socialIcon">
                        <img src="https://koochita.com/images/icons/twitter.png" style="width: 100%; height: 100%">
                    </a>
                    <div class="socialName"></div>
                </div>
                <div class="footerSocial">
                    <a href="#" class="socialIcon">
                        <img src="https://koochita.com/images/icons/instagram.png" style="width: 100%; height: 100%">
                    </a>
                    <div class="socialName"></div>
                </div>
                <div class="footerSocial">
                    <a href="#" class="socialIcon">
                        <img src="https://koochita.com/images/icons/telegram.png" style="width: 100%; height: 100%">
                    </a>
                    <div class="socialName"></div>
                </div>
                <div class="footerSocial">
                    <a href="#" class="socialIcon">
                        <img src="https://koochita.com/images/icons/aparat.png" style="width: 100%; height: 100%">
                    </a>
                    <div class="socialName"></div>
                </div>
            </div>


            {{--            <div class="footerRight">--}}
{{--                <div style="padding: 20px">--}}
{{--                    <img src="https://koochita.com/images/icons/mainLogo.png" class="footerLogo">--}}
{{--                    <div class="footerText">--}}
{{--                        آدرس: .....--}}
{{--                    </div>--}}
{{--                    <div class="footerText">--}}
{{--                        تلفن پشتیبانی: .....--}}
{{--                    </div>--}}
{{--                    <div class="footerText" style="margin-top: 60px">--}}
{{--                        شاید بخواهید در خصوص حریم خصوصی و قوانین سایت بیشتر بدانید، در صورت نیاز به کمک صفحه راهنما را بخوانید و در صورت نیاز با ما تماس بگیرید . این سایت متعلق به مجموعه کوچیتا می باشد. درباره ی ما بیشتر بدانید.--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="footerLeft">--}}
{{--                <div style="font-size: 25px; font-weight: bold; margin-bottom: 30px">--}}
{{--                    در رسانه ها با ما باشید--}}
{{--                </div>--}}
{{--                <div class="footerSocialDiv">--}}
{{--                    <div class="footerSocial">--}}
{{--                        <a href="#" class="socialIcon">--}}
{{--                            <img src="https://koochita.com/images/icons/linkedin.png" style="width: 100%; height: 100%">--}}
{{--                        </a>--}}
{{--                        <div class="socialName"></div>--}}
{{--                    </div>--}}
{{--                    <div class="footerSocial">--}}
{{--                        <a href="#" class="socialIcon">--}}
{{--                            <img src="https://koochita.com/images/icons/facebook.png" style="width: 100%; height: 100%">--}}
{{--                        </a>--}}
{{--                        <div class="socialName"></div>--}}
{{--                    </div>--}}
{{--                    <div class="footerSocial">--}}
{{--                        <a href="#" class="socialIcon">--}}
{{--                            <img src="https://koochita.com/images/icons/twitter.png" style="width: 100%; height: 100%">--}}
{{--                        </a>--}}
{{--                        <div class="socialName"></div>--}}
{{--                    </div>--}}
{{--                    <div class="footerSocial">--}}
{{--                        <a href="#" class="socialIcon">--}}
{{--                            <img src="https://koochita.com/images/icons/instagram.png" style="width: 100%; height: 100%">--}}
{{--                        </a>--}}
{{--                        <div class="socialName"></div>--}}
{{--                    </div>--}}
{{--                    <div class="footerSocial">--}}
{{--                        <a href="#" class="socialIcon">--}}
{{--                            <img src="https://koochita.com/images/icons/telegram.png" style="width: 100%; height: 100%">--}}
{{--                        </a>--}}
{{--                        <div class="socialName"></div>--}}
{{--                    </div>--}}
{{--                    <div class="footerSocial">--}}
{{--                        <a href="#" class="socialIcon">--}}
{{--                            <img src="https://koochita.com/images/icons/aparat.png" style="width: 100%; height: 100%">--}}
{{--                        </a>--}}
{{--                        <div class="socialName"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
</body>


@yield('script')
</html>
