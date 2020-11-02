@extends('layout.mainLayout')


@section('head')
    <title> صفحه {{$user->username}} </title>
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:type" content="website" />
    <meta name="title" content="کوچیتا |  صفحه {{$user->username}} " />
    <meta name='description' content=' صفحه {{$user->username}} ' />
    <meta name='keywords' content='ک صفحه {{$user->username}} ' />
    <meta property="og:image" content="{{ asset('images/blank.jpg') }}"/>
    <meta property="og:image:secure_url" content="{{ asset('images/blank.jpg') }}"/>
    <meta property="og:image:width" content="550"/>
    <meta property="og:image:height" content="367"/>
    <meta name="twitter:image" content="{{ asset('images/blank.jpg') }}"/>

@endsection


@section('body')
    <div class="profileTopSection">
        <div class="bannerSec">
            @if($yourPage == 1)
                <label for="bannerInput" class="editBannerButton editPicIcon"></label>
                <input id="bannerInput" type="file" onchange="editProfileBannerPic(this)" style="display: none;">
            @endif
            <img id="bannerPic" src="{{$user->bannerPic}}" class="resizeImgClass" onload="resizeThisImg(this)">
        </div>
        <div class="userMainPicSec">
            <div class="uPic">
                <img src="{{$user->pic}}" style="width: 100%">
            </div>
            <div class="uName">
                <div class="name">{{$user->username}}</div>
{{--                <div class="follower">0 دنبال کننده</div>--}}
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
        <div id="homeTab" class="profilePage tabBody">
            @include('page.profile.mainProfileInner.innerProfileHome')
        </div>
        <div id="videosTab" class="profilePage tabBody hidden">
            <div class="headerWithLine">
                <div class="headerWithLineText">
                    تمامی ویدیو ها
                </div>
            </div>
            <div id="allVideo" class="allVideoList"></div>
        </div>
        <div id="playListTab" class="profilePage tabBody hidden">
            @if($yourPage == 1)
                <button class="bigBlueButtonNonCorner plusIcon" onclick="openPlayListEditModal()">ویرایش لیست های پخش</button>
            @endif
            <div class="headerWithLine">
                <div class="headerWithLineText">
                    لیست های پخش
                </div>
            </div>
            <div id="allPlayListDiv" class="allVideoList"></div>
        </div>
        @if($yourPage == 1)
            <div id="bookMarkTab" class="profilePage tabBody hidden">
                <div class="headerWithLine">
                    <div class="headerWithLineText">
                        نشان کرده ها
                    </div>
                </div>
                <div id="allBookMarkedVideo" class="allVideoList"></div>
            </div>
        @endif
    </div>

    <div id="playListEditModal" class="myModal">
        <div class="myBody">
            <div class="myExit closeIcon closeThisMyModal"></div>
            <div class="title">
                لیست های پخش
                <button class="addButton plusIcon" onclick="openMyModal('addNewPlayListModal')"></button>
            </div>
            <div id="playListEditBodyModal" class="playListEditBodyModal content"></div>
            <div class="footer">
                <button class="footerBtn closeB closeThisMyModal">بستن</button>
            </div>
        </div>
    </div>

    <div id="allVideoModal" class="myModal">
        <div class="myBody">
            <div class="myExit closeIcon closeThisMyModal"></div>
            <div class="title"></div>
            <div class="topVideoModal content">
                <div class="searchBar">
                    <input id="inputForSearchInAllVideos" type="text" placeholder="نام ویدیو را برای جستجو وارد کنید..." onkeyup="searchForVideoInAllVideosModal(this.value)">
                </div>
                <div id="bodyForAllVideoModalSearch" class="resultSearch"></div>
            </div>
            <div class="footer">
                <button class="footerBtn submit" onclick="submitAllVideoModal()">تایید</button>
                <button class="footerBtn closeB closeThisMyModal">بستن</button>
            </div>
        </div>
    </div>

    <div id="addNewPlayListModal" class="myModal">
        <div class="myBody" style="width: 400px;">
            <div class="myExit closeIcon closeThisMyModal"></div>
            <div class="title">ایجاد لیست پخش جدید</div>
            <div class="topVideoModal content">
                <input id="newPlayListNameInput" class="newPlayListNameInput" type="text" placeholder="نام لیست پخش را وارد نمایید">
            </div>
            <div class="footer">
                <button class="footerBtn submit" onclick="submitNewPlayListName()">تایید</button>
                <button class="footerBtn closeB closeThisMyModal">بستن</button>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script>
        var submitAllVideoModalCallBack = null;
        var selectedTypeInAllVideoModal = 'single';
        var allUserPlayList = {!! json_encode($allPlayList) !!};
        var showAblePlayList = {!! json_encode($playListsVideos) !!};
        var allUserVideos = {!! $allVideos !!};
        var allVideoBookMark = {!! $bookMarked !!};
        var selectVideoForPlayListId = 0;
        var deletedPlayListId = 0;

        window.deletePlayListUrl = '{{route("playList.delete")}}';
        window.editVideoListInPlayList = '{{route("playList.edit.videoList")}}';
        window.editPlayListNameUrl = '{{route('playList.edit.name')}}';
        window.editPlayListVideoSortUrl = '{{route("playList.edit.updateVideoSort")}}';
        window.deleteVideoFromPlayListUrl = '{{route("playList.edit.deleteVideo")}}';
        window.newPlayListStoreUrl = '{{route("playList.new")}}';
        window.updateProfileBannerPic = '{{route('profile.updateBanner')}}';
    </script>

    <script src="{{URL::asset('js/pages/mainProfile.js?v='.$fileVersion)}}"></script>
@endsection
