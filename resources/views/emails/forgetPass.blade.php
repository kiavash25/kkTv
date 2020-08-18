@extends('emails.layout')


@section('head')
    <style>
        .mainText{
            padding: 10px;
            font-size: 18px;
        }
        .buttonDiv{
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0px;
        }
        .redirectButton{
            padding: 15px 40px;
            background: var(--koochita-dark-green);
            color: white !important;
            text-decoration: none;
            border-radius: 24px;
            margin: 0px auto;
        }
        .redirectButton:hover{
            color: white;
            text-decoration: none;

        }
        .mainLink{
            font-size: 14px;
            direction: ltr;
            text-align: left;
            margin: 15px;
        }
        .warningText{
            color: darkred;
            font-size: 15px;
            margin-bottom: 60px
        }
    </style>
@endsection

@section('body')
    <div class="mainText">
        شما در سایت koochita.com درخواست بازیابی رمز عبور داده اید. با استفاده از دکمه زیر می توانید اقدام به تغییر رمز عبور کنید.
    </div>

    <div class="buttonDiv">
        <a href="{{$link}}" class="redirectButton">
            بازیابی رمز عبور
        </a>
    </div>

    <div class="mainText">
        در صورت وجود مشکلی از لینک زیر می توانید استفاده نمایید.
    </div>

    <div class="mainLink">
        <a href="{{$link}}">{{$link}}</a>
    </div>

    <div class="mainText warningText">
        در صورتی که این ایمیل به اشتباه برای شما ارسال شده است صرفا آن را نادیده بگیرید.
    </div>
@endsection


@section('script')

@endsection