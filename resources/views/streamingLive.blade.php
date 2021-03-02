@extends('layout.mainLayout')

@section('head')
    <link rel="stylesheet" href="{{URL::asset('css/pages/videoShow.css')}}">

    <link href="https://vjs.zencdn.net/7.7.5/video-js.css" rel="stylesheet" />

    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src="https://vjs.zencdn.net/7.7.5/video.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.15.0/videojs-contrib-hls.min.js"></script>


    <title>
        پخش زنده
        {{$video->title}}
    </title>
    <style>

        #videoThisVideo{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-height: 530px;
            overflow: auto;
        }
        #videoThisVideo > div{
            margin-bottom: 10px;
            width: 49%;
        }
        .mainShowBase{
            padding: 15px 5px;
            width: calc(100% - 320px);
        }
        .pcGuestSide{
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            flex-wrap: wrap;
        }
        .liveChatSec{
            border-bottom: solid 2px #232323;
            margin-bottom: 15px;
        }
        .liveChatSec .lastChats{
            height: 0px;
            transition: .3s;
            overflow: hidden;
            margin-bottom: 15px;
            background: #232323;
            border-radius: 10px;
        }
        .liveChatSec .lastChats .showLastChats{
            height: 100%;
            overflow: auto;
        }
        .liveChatSec .lastChats .chatRow{
            border-bottom: solid;
            margin-bottom: 10px;
        }
        .liveChatSec .lastChats .chatRow .userName{
            color: gray;
            font-size: 10px;
        }
        .liveChatSec .lastChats .chatRow .text{
            margin-right: 10px;
            margin-bottom: 5px;
            color: white;
            font-weight: 300;
        }
        .inputYouChat{
            display: flex;
            flex-direction: column;
            padding: 0px 5px;
        }
        .userInfo{
            display: flex;
            align-items: center;
            color: white;
            font-size: 15px;
            margin-bottom: 10px;
        }
        .userInfo .pic{
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 50%;
        }
        .userInfo .userName{
            margin-right: 10px;
        }
        .inputYouChat .inputRow{
            display: flex;
        }
        .inputYouChat input{
            width: 100%;
            background: #3a3a3a;
            border: none;
            border-bottom: solid 1px gray;
            color: white;
            height: 30px;
        }
        .inputYouChat .submitChat{
            color: #232323;
            transform: rotate(225deg);
            font-size: 30px;
            display: flex;
            justify-content: center;
            width: 45px;
            margin-right: auto;
            cursor: pointer;
        }


        .liveChatSec.open .downArrowIconAfter:after{
            transform: rotate(180deg)
        }
        .liveChatSec.open .lastChats{
            height: 400px;
            padding: 10px;
        }
        .liveInfosAndComments .title, .liveChatSec .title{
            font-size: 20px;
            color: var(--koochita-yellow);
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 6px;
        }

        .mainDivStream{
            width: 90%;
        }



        .videoBanner{
            width: 100%;
            height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .remainingTime{
            position: absolute;
            width: 100%;
            height: 100%;
            background: #00000052;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-size: 40px;
        }
        .remainingTime .timeToStart{
            font-size: 1.8em;
        }
        .remainingTime .name{
            font-size: 1.3em;
            font-weight: bold;
            text-align: center;
        }
        @media (max-width: 991px) {
            .mainDivStream{
                width: 95%;
            }
            .mainShowBase {
                width: 100% !important;
            }
        }
        @media (max-width: 767px) {
            .remainingTime{
                font-size: 23px;
            }
        }
    </style>
@endsection

@section('body')

    <div class="mainDivStream">
        <div class="container mainShowBase hideOnSmall" style="width: 320px">
            <div class="liveInfosAndComments">
                @if($video->haveChat)
                    <div class="liveChatSec open">
                        <div class="row title downArrowIconAfter" style="cursor: pointer" onclick="closeLiveChatSection()"> گفتگو زنده </div>
                        <div class="lastChats">
                            <div class="showLastChats"></div>
                        </div>
                        <div class="inputYouChat">
                            @if($user != null)
                                <div class="userInfo">
                                    <div class="pic">
                                        <img src="{{$user->pic}}" style="width: 100%">
                                    </div>
                                    <div class="userName">{{$user['username']}}</div>
                                </div>
                            @endif
                            <div class="inputRow">
                                <input type="text" class="liveChatInput" placeholder="تو گفتگو شرکت کن..." onfocus="checkLogin()" >
                                <div class="submitChat sendIcon" onclick="sendLiveChat($(this).prev().val())"></div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row title"> مهمانان برنامه </div>
                <div class="pcGuestSide">
                    @foreach($video->guest as $item)
                        <div class="row guestRow">
                            <div class="guestSection">
                                <div class="guestMainSection {{$item->text != null ? 'setMarginInGuestSection' : ''}}">
                                    <div class="col-md-12" style="display: flex; justify-content: center; padding: 0px">
                                        <div class="guestPicSection">
                                            <img src="{{$item->pic}}" style="width: 100%;">
                                        </div>
                                    </div>
                                    <div class="col-md-12 guestName">
                                        {{$item->name}}
                                    </div>
                                    <div class="col-md-12 guestAction" >
                                        {{$item->action}}
                                    </div>
                                </div>
                                @if($item->text != null)
                                    <div class="guestSideSection setMarginInGuestSection">
                                        <div class="guestText">
                                            {{$item->text}}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        <div class="container mainShowBase">
            <div class="darkShadowBox" style="border-radius: 5px;">
                <div class="showVideo">
                    <div class="videoContainer">
                        <video id="video_1" class="video-js playads" controls style="width: 100%; direction: ltr; display: {{$startVideo == 1 ? 'block' : 'none'}};" data-setup='{"fluid": true}'></video>
                        <div id="bannerSection" class="videoBanner">
                            <div class="remainingTime">
                                <div class="timeToStart"></div>
                                <div> مانده تا شروع</div>
                                <div class="name">{{$video->title}}</div>
                            </div>
                            <img src="{{$video->banner}}" style="height: 100%; display: {{$startVideo == 1 ? 'none' : 'block'}};">
                        </div>
                        <div class="liveCommentsOnFS display-none">
{{--                            <div class="videoInfos">--}}
{{--                                <div class="videoInfosVideoName">--}}
{{--                                    NAME--}}
{{--                                    <img class="float-left" src="{{URL::asset('images/mainPics/live.png')}}">--}}
{{--                                </div>--}}
{{--                                <div class="row mainUserPicSection">--}}
{{--                                    <div class="userPicDiv">--}}
{{--                                        <img src="{{URL::asset('_images/nopic/blank.jpg')}}" alt="koochita">--}}
{{--                                    </div>--}}
{{--                                    <div class="mainUserInfos">--}}
{{--                                        <div class="mainUseruserName">--}}
{{--                                            koochita--}}
{{--                                        </div>--}}
{{--                                        <div class="videoUploadTime">--}}
{{--                                            هم اکنون--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            @if($video->haveChat)
                                <div class="liveCommentsToggleBar" onclick="liveCMToggle(this)">
                                    <div class="textToggle">نمایش پنل گفتگو</div>
                                    <div class="iconToggle glyphicon glyphicon-chevron-down"></div>
                                </div>

                                <div class="liveComments display-none">
                                    <div class="liveCommentsFirstLine">
                                        <div class="liveCommentsTitle"> در گفتگو شرکت کنید </div>
                                        <div class="liveCommentStatistics">
                                            <div class="liveCommentsQuantity liveCommentStatisticsDivs">
                                                <div class="liveCommentsNums chatCount">{{count($chats)}}</div>
                                                <div class="liveCommentsQuantityIcon"></div>
                                            </div>
                                            <div class="liveCommentWriters liveCommentStatisticsDivs">
                                                <div class="liveCommentsNums uniqueUserChat">{{$video->uniqueUser}}</div>
                                                <div class="liveCommentsWriterIcon "></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="liveCommentsMainDiv"></div>

                                    @if(auth()->check())
                                        <div class="commentInputSection">
                                            <div class="userPicDiv">
                                                <img src="{{$userPicture}}" alt="koochita">
                                            </div>
                                            <textarea class="commentInput" name="comment" id="comment" placeholder="شما چه نظری دارید؟" rows="1"></textarea>
                                            <div class="commentInputSendButton" onclick="sendMsg(this)">ارسال</div>
                                        </div>
                                    @else
                                        <div class="commentInputSection">
                                            <div class="commentInputSendButton login-button">ورود</div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <table style="width: 100%;" id="rooms-list"></table>

                <div class="toolSection">
                    <div class="toolSectionButtons">
                        <div class="iconButton LikeIconEmptyAfter likeVideoButton {{$video->youLike == 1 ? 'fill' : ''}}" onclick="setFeedback(1)">{{$video->likeCount}}</div>
                        <div class="iconButton DisLikeIconEmptyAfter disLikeVideoButton {{$video->youLike == -1 ? 'fill' : ''}}" onclick="setFeedback(-1)">{{$video->disLikeCount}}</div>
                    </div>
                    <div class="toolSectionInfos">
                        <div class="iconButton shareIcon share_pic">
                            @include('component.shareBox')
                        </div>
                        <div class="toolSectionInfosTab">
                            <span id="nowUserSeen" class="toolSectionInfosTabNumber hidden"></span>
                            <img src="{{URL::asset('images/mainPics/eye.png')}}" class="eyeClass" style="width: 25px">
                        </div>
                    </div>
                </div>
            </div>

            @if($video->haveChat)
                <div class="liveChatSec open hideOnWide" style="margin-top: 20px;">
                    <div class="row title downArrowIconAfter" style="cursor: pointer" onclick="closeLiveChatSection()"> گفتگو زنده </div>
                    <div class="lastChats">
                        <div class="showLastChats"></div>
                    </div>
                    <div class="inputYouChat">
                        @if($user != null)
                            <div class="userInfo">
                                <div class="pic">
                                    <img src="{{$user->pic}}" style="width: 100%">
                                </div>
                                <div class="userName">{{$user['username']}}</div>
                            </div>
                        @endif
                        <div class="inputRow">
                            <input type="text" class="liveChatInput" placeholder="تو گفتگو شرکت کن..." onfocus="checkLogin()" >
                            <div class="submitChat sendIcon" onclick="sendLiveChat($(this).prev().val())"></div>
                        </div>
                    </div>
                </div>
            @endif


            <div class="videoInfosVideoName" style="color: white; font-size: 25px; margin: 15px 0px;">
                {{$video->title}}
                <img class="float-left" src="{{URL::asset('images/mainPics/live.png')}}">
            </div>
            @if($video->description != '')
                <div class="descriptionSection">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            معرفی کلی
                        </div>
                    </div>
                    <div class="descriptionSectionBody">
                        {{$video->description}}
                    </div>
                </div>
            @endif

            <div class="guestNotPcSection">
                <div class="row">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            مهمانان برنامه
                        </div>
                    </div>
                </div>
                <div class="guestPhoneRows">
                    @foreach($video->guest as $item)
                        <div class="row guestRow">
                            <div class="guestSection">
                                <div class="guestMainSection {{$item->text != null ? 'setMarginInGuestSection' : ''}}">
                                    <div class="col-md-12" style="display: flex; justify-content: center; padding: 0px">
                                        <div class="guestPicSection">
                                            <img src="{{$item->pic}}" style="width: 100%;">
                                        </div>
                                    </div>
                                    <div class="col-md-12 guestName">
                                        {{$item->name}}
                                    </div>
                                    <div class="col-md-12 guestAction" >
                                        {{$item->action}}
                                    </div>
                                </div>
                                @if($item->text != null)
                                    <div class="guestSideSection setMarginInGuestSection">
                                        <div class="guestText">
                                            {{$item->text}}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{URL::asset('js/app.js')}}"></script>

    <script>
{{--        let nonPic = '{{URL::asset('_images/nopic/blank.jpg')}}';--}}
        // var swiper = new Swiper('.videoSuggestionSwiper', {
        //     slidesPerGroup: 1,
        //     // width: 300,
        //     loop: true,
        //     loopFillGroupWithBlank: true,
        //     navigation: {
        //         nextEl: '.swiper-button-next',
        //         prevEl: '.swiper-button-prev',
        //     },
        //     breakpoints: {
        //         650: {
        //             slidesPerView: 1,
        //             spaceBetween: 0,
        //         },
        //         860: {
        //             slidesPerView: 2,
        //             spaceBetween: 20,
        //         },
        //         1600: {
        //             slidesPerView: 3,
        //             spaceBetween: 20,
        //         },
        //         10000: {
        //             slidesPerView: 4,
        //             spaceBetween: 20,
        //         }
        //     }
        // });

        var room = '{{$room}}';
        var showUser = false;

        var setBottom = true;
        var updateChatTimeOut;
        var myPlayer = videojs('video_1', {autoplay: 'true'});

        function closeLiveChatSection(){
            $('.liveChatSec').toggleClass('open');
        }

        function setFeedback(_like){
            if (!hasLogin) {
                showLoginPrompt();
                return;
            }

            $.ajax({
                type: 'post',
                url: '{{route("streaming.live.setLiveFeedback")}}',
                data :{
                    _token: '{{csrf_token()}}',
                    room: room,
                    like: _like,
                },
                success: function(response){
                    if(response.status == 'ok'){
                        var likeVideoButtonElement = $('.likeVideoButton');
                        var disLikeVideoButtonElement = $('.disLikeVideoButton');
                        likeVideoButtonElement.removeClass('fill');
                        disLikeVideoButtonElement.removeClass('fill');

                        if(response.youLike == 1)
                            likeVideoButtonElement.addClass('fill');
                        else if(response.youLike == -1)
                            disLikeVideoButtonElement.addClass('fill');

                        likeVideoButtonElement.text(response.like);
                        disLikeVideoButtonElement.text(response.disLike);
                    }
                }
            })
        }

        function checkLiveStarted(){
            var additionalData = '';

            @if(isset($thisIsTest))
                additionalData = '&thisIsTest=1';
            @endif
            $.ajax({
                timeout: 5000,
                type: 'GET',
                url: '{{route("streaming.getLiveUrl")}}?room='+room+additionalData,
                success: response => {
                    var status = response.status;
                    if(status == 'ok')
                        createVideoTag(response.url);
                    else if(status == 'notTime')
                        setTimeout(checkLiveStarted, 5000);
                    else if(status == 'error2') {
                        alert('ویدیو اماده پخش نیست');
                        location.href = '{{route('index')}}'
                    }
                },
                error: err => setTimeout(checkLiveStarted, 5000)
            })
        }

        function createVideoTag(_url) {
            $('#bannerSection').css('display', 'none');
            $('#video_1').css('display', 'block');
            myPlayer.src({
                src: _url,
                autoplay: true,
                type: 'application/x-mpegURL',
                withCredentials: false,
            });
            showUser = true;
        }

        function sendSeenPageLog(){
            $.ajax({
                type: 'post',
                url: '{{route('streaming.getLiveUserSeen')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    seenPageLogId: window.seenPageLogId,
                    isMobile: window.isMobile,
                    width: $(window).width(),
                    height: $(window).height(),
                    url: document.location.pathname
                },
                success: response => {
                    if(response.status == 'ok') {
                        window.seenPageLogId = response.seenPageLogId;
                        if(showUser)
                            $('#nowUserSeen').text(response.userSeenCount);
                    }
                    setTimeout(sendSeenPageLog, 30000);
                },
                error: err => setTimeout(sendSeenPageLog, 30000)
            })
        }
        sendSeenPageLog();

        @if($startVideo == 1)
            createVideoTag('{{$video->url}}');
        @else
            var countDownDate  = new Date("{{$video->date}} {{$startVideo}}").getTime();
            var timeCounter = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                hours = (hours < 10 ? '0' : '') + hours;
                minutes = (minutes < 10 ? '0' : '') + minutes;
                seconds = (seconds < 10 ? '0' : '') + seconds;

                $('.timeToStart').html(`${hours}:${minutes}:${seconds}`);
                if (distance <= 0) {
                    clearInterval(timeCounter);
                    checkLiveStarted();
                }
            }, 1000);

        @endif
    </script>

    @if($video->haveChat)
        <script>
            let lastChats = {!! json_encode($chats) !!};
            let lastChatId = {{$lastChatId}};
            var updateLiveChatTime = 7000;
            var userSeenTimer = 0;

            @if(auth()->check())
                window.userPic = '{{$user->pic}}';
                $('.liveChatInput').keydown(e => {
                    if(e.keyCode == 13)
                        sendLiveChat($(e.target).val());
                });

                function sendLiveChat(_text){
                    if (!checkLogin)
                        return;
                    if(_text.trim().length > 0){
                        $.ajax({
                            type: 'post',
                            url: '{{route('streaming.storeLiveChat')}}',
                            data: {
                                _token: '{{csrf_token()}}',
                                room: '{{$room}}',
                                text: _text,
                                userPic: window.userPic,
                            },
                            success: response => {
                                if(response.status == 'ok') {
                                    $('.liveChatInput').val('');
                                    clearTimeout(updateChatTimeOut);
                                    updateLiveChat();
                                }
                            },
                        })
                    }
                }
            @endif

            $('.showLastChats').on('scroll', function() {
                setBottom = $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight;
            });

            function updateLiveChat(){
                $.ajax({
                    type: 'get',
                    url: '{{route("streaming.getChats", ['room' => $room])}}?lastId='+lastChatId,
                    success: response => {
                        if(response.status == 'ok'){
                            lastChatId = response.lastChatId;
                            if(userSeenTimer == 5){
                                userSeenTimer = 0;
                                $('#nowUserSeen').text(response.userSeen);
                            }
                            else
                                userSeenTimer++;

                            createChatRow(response.chats);
                        }
                        updateChatTimeOut = setTimeout(updateLiveChat, updateLiveChatTime);
                    },
                    error: err =>{
                        updateChatTimeOut = setTimeout(updateLiveChat, updateLiveChatTime);
                    },
                })
            }

            function createChatRow(_chats){
                var lastChatElements = $('.showLastChats');
                var html = '';
                _chats.map(item => {
                    html += `<div class="chatRow">
                              <div class="userInfo">
                                  <div class="pic" style="width: 20px; height: 20px;">
                                       <img src="${item.userPic}" style="width: 100%">
                                  </div>
                                   <div class="userName" style="margin-right: 5px">${item.username}:</div>
                              </div>
                               <div class="text">${item.text}</div>
                           </div>`;
                });

                lastChatElements.append(html);

                if(setBottom) {
                    for (let i = 0; i < lastChatElements.length; i++)
                        $(lastChatElements[i]).scrollTop(lastChatElements[i].scrollHeight);
                }
            }

            function liveCMToggle(element) {
                $(element).next().toggle()
            }

            createChatRow({!! $chats !!});
            updateLiveChat();
        </script>
    @endif

@endsection
