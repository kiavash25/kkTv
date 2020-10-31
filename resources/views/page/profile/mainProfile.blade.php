@extends('layout.mainLayout')


@section('head')

    <style>
        /*.streamBody{*/
        /*    padding: 0px !important;*/
        /*}*/
        .profileTopSection{
            width: 100%;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .bannerSec {
            width: 100%;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .userMainPicSec{
            position: absolute;
            right: 50px;
            bottom: -50px;
            display: flex;
            align-items: center;
            background: #232323;
            /*background: #3a3a3a;*/
            border-radius: 35px 50px 50px 35px;
            padding: 5px;
            padding-left: 40px;
            box-shadow: 3px 7px 6px 0px #000000;
        }
        .userMainPicSec .uPic{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
        }
        .userMainPicSec .uName{
            margin-right: 20px;
        }
        .uName .name{
            color: white;
            font-size: 18px;
        }
        .uName .follower{
            color: gray;
            font-size: 10px;
        }

        .profilePageHeader{
            width: 100%;
            border-radius: 0px;
            padding-bottom: 0;
            padding-top: 80px;
        }
        .profilePageHeader .hTab{
            display: flex;
            color: white;
        }
        .profilePageHeader .hTab .tab{
            width: 150px;
            text-align: center;
            padding: 0px 0px 10px 0px;
            font-size: 20px;
            color: #8a8a8a;
            transition: .3s;
            cursor: pointer;
        }
        .profilePageHeader .hTab .tab.selected{
            border-bottom: solid;
            color: white;
        }
        .profilePageHeader .hTab .tab:hover{
            color: white !important;
        }

        .tabBody{
            padding: 25px;
            width: 100%;
        }
        .changeHeaderColor .headerWithLineText{
            background: #232323 !important;
        }

        @media (max-width: 767px) {
            .userIntroRow{
                flex-direction: column;
                max-height: 2000px !important;
            }
            .userIntroRow .videoSec{
                width: 100% !important;
            }
            .userIntroRow .infoSec{
                width: 100% !important;
            }
            .userMainPicSec{
                flex-direction: column;
                padding-left: 5px;
                border-radius: 50px 50px 35px 35px;
                right: auto;
                left: auto;
            }
            .userMainPicSec .uPic{
                margin-bottom: 10px;
            }
            .userMainPicSec .uName{
                margin: 0;
            }
        }
    </style>
@endsection


@section('body')
    <div class="profileTopSection">
        <div class="bannerSec">
            <img src="https://static.koochita.com/_images/video/category/1594566080BANNER4.jpg" class="resizeImgClass" onload="resizeThisImg(this)">
        </div>
        <div class="userMainPicSec">
            <div class="uPic">
                <img src="{{$user->pic}}" style="width: 100%">
            </div>
            <div class="uName">
                <div class="name">{{$user->username}}</div>
                <div class="follower">0 دنبال کننده</div>
            </div>
        </div>
    </div>

    <div class="profilePageHeader mainShowBase">
        <div class="hTab">
            <div class="tab selected" onclick="changeTab(this, 'home')">خانه</div>
            <div class="tab" onclick="changeTab(this, 'videos')">ویدیوها</div>
            <div class="tab" onclick="changeTab(this, 'playList')">لیست پخش</div>
            @if($yourPage == 1)
                <div class="tab" onclick="changeTab(this, 'bookMark')">نشان کرده ها</div>
            @endif
            {{--<div class="tab" onclick="changeTab(this, 'category')">دسته بندی ها</div>--}}
        </div>
    </div>

    <div class="changeHeaderColor" style="width: 100%">
        <div id="homeTab" class="tabBody">
            @include('page.profile.mainProfileInner.innerProfileHome')
        </div>
        <div id="videosTab" class="tabBody hidden">
            <div class="headerWithLine">
                <div class="headerWithLineText">
                    تمامی ویدیو ها
                </div>
            </div>
            <div id="allVideo" class="allVideoList"></div>
        </div>
        <div id="playListTab" class="tabBody hidden">
            <div class="headerWithLine">
                <div class="headerWithLineText">
                    لیست پخش ها
                </div>
            </div>
            <div id="allPlayListDiv" class="allVideoList"></div>
        </div>
        @if($yourPage == 1)
            <div id="bookMarkTab" class="tabBody hidden">
                <div class="headerWithLine">
                    <div class="headerWithLineText">
                        نشان کرده ها
                    </div>
                </div>
                <div id="allBookMarkedVideo" class="allVideoList"></div>
            </div>
        @endif
        {{--        <div id="categoryTab" class="tabBody hidden">--}}
        {{--            @include('page.profile.mainProfileInner.innerProfileCategory')--}}
        {{--        </div>--}}
{{--        @include('page.profile.mainProfileInner.innerProfileHome')--}}
    </div>
@endsection


@section('script')
    <script>
        var allUserPlayList = {!! json_encode($playListsVideos) !!};
        var allUserVideos = {!! $allVideos !!};
        var allVideoBookMark = {!! $bookMarked !!};

        createVideoSuggestionDiv(allUserVideos, 'allVideo', () => $('#allVideo').find('.videoSuggestion').addClass('videoInList'), true);
        createVideoSuggestionDiv(allVideoBookMark, 'allBookMarkedVideo', () => $('#allBookMarkedVideo').find('.videoSuggestion').addClass('videoInList'), true);
        $('#allPlayListDiv').html(createPlayListObjGroups(allUserPlayList) /**in playListObj.blade.php**/);

        function changeTab(_element, _tab){
            $('.hTab').find('.tab').removeClass('selected');
            $(_element).addClass('selected');

            $('.tabBody').addClass('hidden');
            $(`#${_tab}Tab`).removeClass('hidden');

            resizeFitImg('resizeImgClass');
            resizeRows('videoInList');
        }
    </script>
@endsection
