@extends('emails.layout')


@section('head')
    <style>
        .picRow{
            display: flex;
            padding: 0px 20px;
            margin: 15px 0px;
        }
        .picTd{
            width: 32%;
            text-align: center;
            overflow: hidden;
            height: 150px;
            display: flex;
            text-decoration: none;
            background-position: center;
        }
        .picTdImg{
            height: 100%;
        }
        .picContent{
            width: 100%;
            text-align: right;
            font-size: 25px;
            font-weight: bold;
            color: #ead0d0;
            background-color: #00000026;
        }
        .picText{
            width: 80%;
            font-size: 22px;
            padding: 10px;
        }
        .withUs{
            text-align: center;
            font-weight: bold;
            font-size: 23px;
            color: var(--koochita-dark-green);
            padding-top: 20px;
        }

        @media (max-width: 600px) {
            .picText{
                width: 90%;
                font-size: 15px;
            }
            .picTd{
                height: 100px;
            }
            .withUs{
                font-size: 17px;
            }
        }
    </style>
@endsection

@section('body')

    <table style="padding: 15px;">
        <tr>
            <td style="font-size: 20px; text-align: justify;">
                خوشحالیم که در کوچیتا میزبان شما هستیم و امیدواریم همیشه میزبان شما بمانیم. ما در کوچیتا برآینم تا سفر را به آسانی یک تصمیم کنیم، تا بتوانید از زیبایی های دنیا و انسان هایی که در آن زندگی می کنند، لذت ببرید. این آرزوی ماست و هیچگاه بدون شما امکان پذیر نمی باشد.
            </td>
        </tr>
        <tr>
            <td class="withUs">
                از امروز شما هم یکی از ما هستید
            </td>
        </tr>
    </table>

    <div class="picRow">
        <a href="https://koochita.com/placeList/12/country" class="picTd" style="background-image: url('https://static.koochita.com/_images/emailPic/amaken.jpg'); background-size: cover;">
            <div class="picContent">
                <div class="picText">
                    بوم گردی های خاص ایران
                </div>
            </div>
        </a>
        <a href="https://koochita.com/placeList/1/country" class="picTd" style="margin: 0 auto; background-image: url('https://static.koochita.com/_images/emailPic/sogat.jpg'); background-size: cover;">
            <div class="picContent">
                <div class="picText">
                    دیدنی های ایران ما
                </div>
            </div>
        </a>
        <a href="https://koochita.com/placeList/11/country" class="picTd" style="background-image: url('https://static.koochita.com/_images/emailPic/food.jpg'); background-size: cover">
            <div class="picContent">
                <div class="picText">
                    غذای محلی ایران ما
                </div>
            </div>
        </a>
    </div>
    <div class="picRow">
        <a href="https://koochita.com/main" class="picTd" style="width: 100%; background-image: url('https://static.koochita.com/_images/emailPic/social.jpg'); background-size: cover;">
            <div class="picContent">
                <div class="picText">
                    فضایی برای گپ درباره سفر، نوشتن خاطرات و انتقاد
                </div>
            </div>
        </a>
    </div>

    <div class="picRow">
        <a href="https://koochita.com/placeList/10/country" class="picTd" style="background-image: url('https://static.koochita.com/_images/emailPic/sanaie.jpg'); background-size: cover;">
            <div class="picContent">
                <div class="picText">
                    سوغات و خوشمزه های ایران ما
                </div>
            </div>
        </a>
        <a href="https://koochita.com/placeList/3/country" class="picTd" style="margin: 0 auto; background-image: url('https://static.koochita.com/_images/emailPic/rest.jpg'); background-size: cover;">
            <div class="picContent">
                <div class="picText">
                    رستوران های ایران ما
                </div>
            </div>
        </a>
        <a href="https://koochita.com/placeList/4/country" class="picTd" style="background-image: url('https://static.koochita.com/_images/emailPic/hotel.jpg'); background-size: cover">
            <div class="picContent">
                <div class="picText">
                    هتل ایران ما
                </div>
            </div>
        </a>
    </div>

    <div class="picRow">
        <a href="https://koochita.com/placeList/6/country" class="picTd" style="width: 100%; background-image: url('https://static.koochita.com/_images/emailPic/jungle.jpg'); background-size: cover;">
            <div class="picContent">
                <div class="picText">
                    کمی با چاشنی ماجراجویی
                </div>
            </div>
        </a>
    </div>

    <div class="picRow">
        <a href="https://koochita.com/badge" class="picTd" style="width: 100%; background-image: url('https://static.koochita.com/_images/emailPic/redcarpet.jpg'); background-size: cover;">
            <div class="picContent">
                <div class="picText">
                    کلی پاداش برای تشکر از همراهی شما
                </div>
            </div>
        </a>
    </div>


@endsection


@section('script')

@endsection