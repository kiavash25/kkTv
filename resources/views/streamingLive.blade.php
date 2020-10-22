@extends('layout.mainLayout')

@section('head')
    <link rel="stylesheet" href="{{URL::asset('css/pages/videoShow.css')}}">

    <link href="https://vjs.zencdn.net/7.7.5/video-js.css" rel="stylesheet" />

    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src="https://vjs.zencdn.net/7.7.5/video.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.15.0/videojs-contrib-hls.min.js"></script>


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

        .mainDivStream{
            width: 90%;
        }
        @media (max-width: 991px) {
            .mainDivStream {
                width: 100%;
            }
        }
    </style>
@endsection

@section('body')

    <div class="mainDivStream">
        <div class="container mainShowBase hideOnSmall" style="width: 200px">
            @if($data['haveVideo'])
                <div class="liveInfosAndComments">
                    <div class="videoInfos">
{{--                        <div class="videoInfosVideoName">--}}
{{--                            {{$data['title']}}--}}
{{--                            <img class="float-left" src="{{URL::asset('images/mainPics/live.png')}}">--}}
{{--                        </div>--}}

{{--                        <div class="row mainUserPicSection">--}}
{{--                            <div class="userPicDiv">--}}
{{--                                <img src="{{$data['user']->pic}}" alt="koochita">--}}
{{--                            </div>--}}
{{--                            <div class="mainUserInfos">--}}
{{--                                <div class="mainUseruserName">--}}
{{--                                    {{isset($data['user']) && $data['user'] != '' ? $data['user']->username : ''}}--}}
{{--                                </div>--}}
{{--                                <div class="videoUploadTime">--}}
{{--                                    هم اکنون--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                    </div>

                    <div class="row" style="color:white; text-align: center">
                        مهمانان برنامه
                    </div>
                    @foreach($data['guest'] as $item)
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

{{--                    <div class="liveComments">--}}
{{--                        <div class="liveCommentsFirstLine">--}}
{{--                            <div class="liveCommentsTitle">--}}
{{--                                در گفتگو شرکت کنید--}}
{{--                            </div>--}}
{{--                            <div class="liveCommentStatistics">--}}
{{--                                <div class="liveCommentsQuantity liveCommentStatisticsDivs">--}}
{{--                                    <div class="liveCommentsNums chatCount">{{count($data['chats'])}}</div>--}}
{{--                                    <div class="liveCommentsQuantityIcon"></div>--}}
{{--                                </div>--}}
{{--                                <div class="liveCommentWriters liveCommentStatisticsDivs">--}}
{{--                                    <div class="liveCommentsNums uniqueUserChat">{{$data['uniqueUser']}}</div>--}}
{{--                                    <div class="liveCommentsWriterIcon "></div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="liveCommentsMainDiv"></div>--}}
{{--                        @if(auth()->check())--}}
{{--                            <div class="commentInputSection">--}}
{{--                                <div class="userPicDiv">--}}
{{--                                    <img src="{{$userPicture}}" alt="koochita">--}}
{{--                                </div>--}}
{{--                                <textarea class="commentInput" name="comment" id="comment" placeholder="شما چه نظری دارید؟" rows="1"></textarea>--}}
{{--                                <div class="commentInputSendButton" onclick="sendMsg(this)">ارسال</div>--}}
{{--                            </div>--}}
{{--                        @else--}}
{{--                            <div class="commentInputSection">--}}
{{--                                <div class="commentInputSendButton login-button">ورود</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}

                </div>
            @endif
        </div>

        <div class="container mainShowBase">
            <div class="darkShadowBox" style="border-radius: 5px;">
                <div class="showVideo">
                    <div class="videoContainer">
                        @if($data['haveVideo'] == true)
                            <video id="video_1" class="video-js playads" controls style="width: 100%; direction: ltr" data-setup='{"fluid": true}'></video>

                            <script>
                                var myPlayer = videojs('video_1', {autoplay: 'any'});
                                myPlayer.src({
                                    {{--src: 'https://streaming.koochita.com/hls/{{$room}}.m3u8',--}}
                                    src: 'https://yom.ir/vod/144p.m3u8',
                                    type: 'application/x-mpegURL',
                                    withCredentials: false
                                });
                            </script>
                        @else
                            <img src="{{URL::asset('images/mainPics/liveBanner.jpg')}}" style="width: 100%">
                        @endif
                        <div class="liveCommentsOnFS display-none">

                            <div class="videoInfos">
                                <div class="videoInfosVideoName">
                                    NAME
                                    <img class="float-left" src="{{URL::asset('images/mainPics/live.png')}}">
                                </div>
                                <div class="row mainUserPicSection">
                                    <div class="userPicDiv">
                                        <img src="{{URL::asset('_images/nopic/blank.jpg')}}" alt="koochita">
                                    </div>
                                    <div class="mainUserInfos">
                                        <div class="mainUseruserName">
                                            koochita
                                        </div>
                                        <div class="videoUploadTime">
                                            هم اکنون
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="liveCommentsToggleBar" onclick="liveCMToggle(this)">
                                <div class="textToggle">نمایش پنل گفتگو</div>
                                <div class="iconToggle glyphicon glyphicon-chevron-down"></div>
                            </div>

                            <div class="liveComments display-none">
                                <div class="liveCommentsFirstLine">
                                    <div class="liveCommentsTitle">
                                        در گفتگو شرکت کنید
                                    </div>
                                    <div class="liveCommentStatistics">
                                        <div class="liveCommentsQuantity liveCommentStatisticsDivs">
                                            <div class="liveCommentsNums chatCount">{{count($data['chats'])}}</div>
                                            <div class="liveCommentsQuantityIcon"></div>
                                        </div>
                                        <div class="liveCommentWriters liveCommentStatisticsDivs">
                                            <div class="liveCommentsNums uniqueUserChat">{{$data['uniqueUser']}}</div>
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

                        </div>
                    </div>
                </div>
                <script>
                    function liveCMToggle(element) {
                        $(element).next().toggle()
                    }
                </script>

                <table style="width: 100%;" id="rooms-list"></table>

                <div class="toolSection">
                    <div class="toolSectionButtons">
                        <div class="iconButton LikeIconEmptyAfter likeVideoButton" onclick="setFeedback(1)">
                            0
                        </div>
                        <div class="iconButton DisLikeIconEmptyAfter disLikeVideoButton " onclick="setFeedback(-1)">
                            0
                        </div>
                        <div class="iconButton CommentIconAfter">
                            0
                        </div>
                    </div>
                    <div class="toolSectionInfos">
                        <div class="iconButton shareIcon share_pic">
                            @include('component.shareBox')
                        </div>
                        <div class="toolSectionInfosTab">
                            <span class="toolSectionInfosTabNumber">0</span>
                            <img src="{{URL::asset('images/mainPics/eye.png')}}" class="eyeClass" style="width: 25px">
                        </div>
                    </div>
                </div>
            </div>


            <div class="liveInfosAndComments hideOnWide">

{{--                <div class="videoInfos">--}}
{{--                    <div class="videoInfosVideoName">--}}
{{--                        {{$data['title']}}--}}
{{--                        <img class="float-left" src="{{URL::asset('images/streaming/live.png')}}">--}}
{{--                    </div>--}}
{{--                    <div class="row mainUserPicSection">--}}
{{--                        <div class="userPicDiv">--}}
{{--                            <img src="{{$data['userPic']}}" alt="koochita">--}}
{{--                        </div>--}}
{{--                        <div class="mainUserInfos">--}}
{{--                            <div class="mainUseruserName">--}}
{{--                                {{isset($data['user']) && $data['user'] != '' ? $data['user']->username : ''}}--}}
{{--                            </div>--}}
{{--                            <div class="videoUploadTime">--}}
{{--                                هم اکنون--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="liveComments">--}}
{{--                    <div class="liveCommentsFirstLine">--}}
{{--                        <div class="liveCommentsTitle">--}}
{{--                            در گفتگو شرکت کنید--}}
{{--                        </div>--}}
{{--                        <div class="liveCommentStatistics">--}}
{{--                            <div class="liveCommentsQuantity liveCommentStatisticsDivs">--}}
{{--                                <div class="liveCommentsNums chatCount">{{count($data['chats'])}}</div>--}}
{{--                                <div class="liveCommentsQuantityIcon"></div>--}}
{{--                            </div>--}}
{{--                            <div class="liveCommentWriters liveCommentStatisticsDivs">--}}
{{--                                <div class="liveCommentsNums uniqueUserChat">{{$data['uniqueUser']}}</div>--}}
{{--                                <div class="liveCommentsWriterIcon "></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="liveCommentsMainDiv"></div>--}}

{{--                    @if(auth()->check())--}}
{{--                        <div class="commentInputSection">--}}
{{--                            <div class="userPicDiv">--}}
{{--                                <img src="{{$userPicture}}" alt="koochita">--}}
{{--                            </div>--}}
{{--                            <textarea class="commentInput" name="comment" id="comment" placeholder="شما چه نظری دارید؟" rows="1"></textarea>--}}
{{--                            <div class="commentInputSendButton" onclick="sendMsg(this)">ارسال</div>--}}
{{--                        </div>--}}
{{--                    @else--}}
{{--                        <div class="commentInputSection">--}}
{{--                            <div class="commentInputSendButton login-button">ورود</div>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                </div>--}}

            </div>

            @if($data['desc'] != '')
                <div class="videoInfosVideoName" style="color: white; font-size: 25px; margin: 15px 0px;">
                    {{$data['title']}}
                    <img class="float-left" src="{{URL::asset('images/mainPics/live.png')}}">
                </div>

                <div class="descriptionSection">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            معرفی کلی
                        </div>
                        <div class="headerWithLineLine"></div>
                    </div>
                    <div class="descriptionSectionBody">
                        {{$data['desc']}}
                    </div>
    {{--                <div class="moreBtn">بیشتر</div>--}}
                </div>
            @endif

            <div class="guestNotPcSection">
                <div class="row">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            مهمانان برنامه
                        </div>
                        <div class="headerWithLineLine"></div>
                    </div>
                </div>
                <div class="guestPhoneRows">
                    @foreach($data['guest'] as $item)
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

{{--        <div class="container mainShowBase lestSideLiveVideo">--}}
{{--            <div class="row" style="color:white; text-align: center">--}}
{{--                مهمانان برنامه--}}
{{--            </div>--}}
{{--            @foreach($data['guest'] as $item)--}}
{{--                <div class="row guestRow">--}}
{{--                    <div class="guestSection">--}}
{{--                        <div class="guestMainSection {{$item->text != null ? 'setMarginInGuestSection' : ''}}">--}}
{{--                            <div class="col-md-12" style="display: flex; justify-content: center; padding: 0px">--}}
{{--                                <div class="guestPicSection">--}}
{{--                                    <img src="{{$item->pic}}" style="width: 100%;">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-12 guestName">--}}
{{--                                {{$item->name}}--}}
{{--                            </div>--}}
{{--                            <div class="col-md-12 guestAction" >--}}
{{--                                {{$item->action}}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        @if($item->text != null)--}}
{{--                            <div class="guestSideSection setMarginInGuestSection">--}}
{{--                                <div class="guestText">--}}
{{--                                    {{$item->text}}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        </div>--}}
    </div>

@endsection

@section('script')

    <script>
        let nonPic = '{{URL::asset('_images/nopic/blank.jpg')}}';

        var swiper = new Swiper('.videoSuggestionSwiper', {
            slidesPerGroup: 1,
            // width: 300,
            loop: true,
            loopFillGroupWithBlank: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                650: {
                    slidesPerView: 1,
                    spaceBetween: 0,
                },
                860: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1600: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                10000: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                }
            }
        });
    </script>

    <script src="{{URL::asset('js/app.js')}}"></script>

    <script>
        let videoData = {!! json_encode($data) !!};
        let lastChats = videoData.chats;

        @if(auth()->check())
            let userPic = '{{$userPicture}}';
            let userName = '{{auth()->user()->username}}';
            let room = '{{$room}}';

            function sendMsg(_element){
                let msg = $(_element).prev().val();
                $(_element).prev().val('');

                if(msg.trim().length > 0) {
                    ajaxMsg(msg);
                }
            }

            function ajaxMsg(_msg){
                $.ajax({
                    type: 'post',
                    url: '{{route("sendBroadcastMsg")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        msg: _msg,
                        userPic: userPic,
                        userName: userName,
                        room: room,
                    },
                    success: function (response) {
                        try{
                            response = JSON.parse(response);
                            $('.chatCount').text(response.count);
                            $('.uniqueUserChat').text(response.uniqueUser);
                        }
                        catch (e) {
                            console.log('error in send chat');
                        }
                    },
                    error: function (err) {
                        console.log('err')
                    }
                })
            }

            $('.commentInput').keydown(function (e) {
                if (e.keyCode == 13){
                    let msg = this.value;
                    if(msg.trim().length > 0) {
                        $('.commentInput').val('');
                        ajaxMsg(msg);
                    }
                }
            });
        @endif

        let setBottom = true;

        $('.liveCommentsMainDiv').on('scroll', function(e){
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight)
                setBottom = true;
            else
                setBottom = false;
        });

        window.Echo.channel('liveComments.{{$room}}')
            .listen('CommentBroadCast', (e) => {
                createCommentRow(e.message, e.username, e.userPic);
            });

        function createCommentRow(_txt, _name, _pic){
            let text = '                      <div class="eachLiveCommentMainDiv">\n' +
                '                                <div class="eachLiveCommentTitle">\n' +
                '                                    <div class="userPicDiv">\n' +
                '                                        <img src="' + _pic + '" alt="koochita">\n' +
                '                                    </div>\n' +
                '                                    <div class="mainUserInfos">\n' +
                '                                        <div class="mainUseruserName">' + _name + '</div>\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                                <div class="liveCommentContents">' + _txt + '</div>\n' +
                '                            </div>\n';

            $('.liveCommentsMainDiv').append(text);
            if(setBottom)
                $('.liveCommentsMainDiv').scrollTop($(this).height());
        }
        lastChats.forEach(item => {
           createCommentRow(item.text, item.username, item.userPic);
        });

        function feedBack(_like){
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
                    response = JSON.parse(response);
                    if(response.status == 'ok'){
                        $('#liveLikeCount').text(response.like);
                        $('#liveDisLikeCount').text(response.disLike);
                    }
                    else
                        console.log(response);
                }
            })
        }


        $(window).resize(function(){
            if($(window).width() > 991) {
                let height = $('#video_1_html5_api').height();
                $('.liveCommentsMainDiv').css('max-height', height - 200);
            }
            else
                $('.liveCommentsMainDiv').css('max-height', 200);
        });



        $(document).ready(function(){
            var videotag = $('.playads');

            if($(window).width() > 991) {
                let height = $('#video_1_html5_api').height();
                $('.liveCommentsMainDiv').css('max-height', height - 200);
            }

            // $(".liveCommentsOnFS").appendTo($('#video_1'));
            //
            // myPlayer.on('fullscreenchange', function() {
            //     $('.liveCommentsOnFS').toggle();
            // });
        });
    </script>

@endsection
