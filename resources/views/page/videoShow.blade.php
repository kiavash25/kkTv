@extends('layout.mainLayout')

@section('head')
    <link rel="stylesheet" href="{{URL::asset('css/pages/videoShow.css?v='.$fileVersion)}}">

    <link href="https://vjs.zencdn.net/5.19.2/video-js.css" rel="stylesheet">
    <style type="text/css">
        .video-js {
            font-size: 1rem;
        }
        .vjs-control-bar {
            direction: ltr !important;
        }
        .vjs-big-play-button {
            top: 45% !important;
            left: 46% !important;
            width: 8% !important;
        }
        .videoShowPageBody{
            display: flex;
            width: 90%;
        }
        .videoPlaces{
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            transition: .4s;
            overflow: hidden;
        }
        .showInPhone{
            display: none;
        }
        .sideSuggestion{
            width: 49% !important;
        }
        .notShowPlace{
            display: none;
        }
        @media (min-width: 992px) {
            .mainShowBase{
                width: 75%;
            }
        }
        @media (max-width: 1400px) {

            .sideSuggestion{
                width: 100% !important;
            }
            .sideSuggestion .videoSugPicSection{
                height: 120px !important;
            }
        }
        @media (max-width: 992px) {
            .showInPhone{
                display: flex;
                flex-direction: column;
            }
            .videoShowPageBody{
                width: 100%;
            }
        }
        .playList .body .item.played:after{
            content: '';
            background-image: url("{{URL::asset('images/mainPics/play.png')}}");
            background-size: 30px;
            width: 30px;
            height: 30px;
            transform: rotate(180deg);
            margin-right: auto;
        }
    </style>
@endsection

@section('body')
    <div class="videoShowPageBody">
        <div class="container mainShowBase hideOnTablet" style="width: 24%; padding: 15px 5px;">
            @if($playList != null)
                <div class="playListSide">
                    <div class="headerWithLine" style="margin-top: 0;">
                        <div class="headerWithLineText">
                            لیست پخش
                        </div>
                    </div>
                    <div class="playList open">
                        <div class="header">{{$playList->name}}</div>
                        <div class="body">
                            @foreach($playList->videoList as $item)
                                <a href="{{$item->url}}" class="item {{$item->id == $video->id ? 'played' : ''}}">
                                    <div class="pic">
                                        <img src="{{$item->pic}}" class="resizeImgClass" style="width: 100%" onload="resizeThisImg(this)">
                                    </div>
                                    <div class="infos">
                                        <div class="name">{{$item->title}}</div>
                                        <div class="category">{{$item->categoryName}}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="footer" onclick="$(this).parent().toggleClass('open')">
                            <i class="arrowCss down"></i>
                        </div>
                    </div>
                </div>
            @endif

            @if($video->hasPlace)
                <div class="placeRelatedSide">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            محل های مرتبط
                        </div>
                    </div>
                    <div class="playList open">
                        <div class="body relatedPlaceBody">
                            <img src="{{URL::asset('images/mainPics/gear.svg')}}" style="margin: 10px auto; width: 50px;">
                        </div>
                        <div class="footer" onclick="$(this).parent().toggleClass('open')">
                            <i class="arrowCss down"></i>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($video->places) && count($video->places) > 0)
                <div class="moreInfoMainDiv">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            اطلاعات بیشتر
                        </div>
                    </div>
                    <div id="pcVideoPlace" class="videoPlaces">
                        @for($i = 0; $i < count($video->places); $i++)
                            <div class="moreInfoEachItem {{$i >= 4 ? 'notShowPlace' : ''}}">
                                <a href="{{$video->places[$i]->url}}" target="_blank" class="mainDivImgMoreInfoItems">
                                    <img src="{{$video->places[$i]->placePic}}" style="width: 100%">
                                </a>
                                <div class="moreInfoItemsDetails">
                                    <a href="{{$video->places[$i]->url}}" target="_blank" class="placeName">
                                        {{$video->places[$i]->name}}
                                    </a>
                                    @if($video->places[$i]->kindPlaceId > 0)
                                        <div class="placeRates">
                                            <div class="rating_and_popularity">
                                                    <span class="header_rating">
                                                       <div class="rs rating" rel="v:rating">
                                                           <div class="prw_rup prw_common_bubble_rating overallBubbleRating float-left">
                                                               <span class="ui_bubble_rating bubble_{{$video->places[$i]->placeRate}}0 font-size-16" property="ratingValue"></span>
                                                           </div>
                                                       </div>
                                                    </span>
                                                <span class="header_popularity popIndexValidation" id="scoreSpanHeader">
                                                    <a>
                                                        {{$video->places[$i]->placeReviews}}
                                                        نقد
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    @if($video->places[$i]->kindPlaceId > -1)
                                        <div class="placeState">استان:
                                            <span>{{$video->places[$i]->placeState}}</span>
                                        </div>
                                    @endif
                                    @if($video->places[$i]->kindPlaceId > 0)
                                        <div class="placeCity">شهر:
                                            <span>{{$video->places[$i]->placeCity}}</span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endfor

                        @if(count($video->places) > 4)
                            <div id="pcMoreBtn" class="moreBtn" onclick="openMorePlace(this)">بیشتر</div>
                        @endif
                    </div>
                </div>
            @endif

            @if(isset($userMoreVideo) && count($userMoreVideo) > 0)
                <div class="fromThisPerson">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            از همین کاربر
                        </div>
                    </div>
                    <div id="videoThisVideo" style="display: flex; align-items: center; flex-wrap: wrap; justify-content: space-evenly"></div>
                </div>
            @endif
        </div>
        <div class="container mainShowBase">
            <div class="darkShadowBox" style="border-radius: 5px;">
                <div class="showVideo">
                    <video id="video_1"
                           class="playads embed-responsive-item video-js vjs-default-skin vjs-16-9"
                           controls
                           data-setup='{"fluid": true, "preload": "none", "auto-play": false }'
                           poster="{{$video->pic}}"
                           style="width: 100%; direction: ltr;"></video>
                </div>
                <div class="toolSection">
                    <div class="toolSectionButtons">
                        <div class="iconButton LikeIconEmptyAfter likeVideoButton {{$video->uLike == 1 ? 'fill' : ''}}" onclick="setFeedback(1)">
                            {{$video->like}}
                        </div>
                        <div class="iconButton DisLikeIconEmptyAfter disLikeVideoButton {{$video->uLike == -1 ? 'fill' : ''}}" onclick="setFeedback(-1)">
                            {{$video->disLike}}
                        </div>
                        <div class="iconButton CommentIconAfter">
                            {{$video->commentsCount}}
                        </div>
                    </div>
                    <div class="toolSectionInfos">
                        <div class="iconButton shareIcon share_pic">
                            @include('component.shareBox')
                        </div>
                        <div class="toolSectionInfosTab">
                            <span class="toolSectionInfosTabNumber">{{$video->seen}}</span>
                            <img src="{{URL::asset('images/mainPics/eye.png')}}" class="eyeClass" style="width: 25px">
                        </div>
                    </div>
                </div>
            </div>

            <div class="videoInfos" style="width: 100%;">
                <div class="videoInfosVideoName">
                    {{$video->title}}
                </div>
                <div class="row mainUserPicSection" style="margin-top: 10px;">
                    <div class="userPicDiv">
                        <img src="{{$video->userPic}}" alt="koochita">
                    </div>
                    <div class="mainUserInfos">
                        <div class="mainUseruserName">
                            {{$video->username}}
                        </div>
                        <div class="videoUploadTime">
                            {{$video->time}}
                        </div>
                    </div>
                </div>
            </div>

            @if($playList != null)
                <div class="playListSide hideOnWide">
                    <div class="headerWithLine" style="margin-top: 0;">
                        <div class="headerWithLineText">
                            لیست پخش
                        </div>
                    </div>
                    <div class="playList open">
                        <div class="header">{{$playList->name}}</div>
                        <div class="body">
                            @foreach($playList->videoList as $item)
                                <a href="{{$item->url}}" class="item {{$item->id == $video->id ? 'played' : ''}}">
                                    <div class="pic">
                                        <img src="{{$item->pic}}" class="resizeImgClass" style="width: 100%" onload="resizeThisImg(this)">
                                    </div>
                                    <div class="infos">
                                        <div class="name">{{$item->title}}</div>
                                        <div class="category">{{$item->categoryName}}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="footer" onclick="$(this).parent().toggleClass('open')">
                            <i class="arrowCss down"></i>
                        </div>
                    </div>

                </div>
            @endif

            @if($video->description != null && $video->description != '')
                <div class="descriptionSection" style="width: 100%">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            معرفی کلی
                        </div>
                    </div>
                    <div class="descriptionSectionBody">
                        {!! $video->description !!}
                    </div>
                </div>
            @endif

            @if($video->hasPlace)
                <div class="placeRelatedSide hideOnWide">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            محل های مرتبط
                        </div>
                    </div>
                    <div class="playList open">
                        <div class="body relatedPlaceBody">
                            <img src="{{URL::asset('images/mainPics/gear.svg')}}" style="margin: 10px auto; width: 50px;">
                        </div>
                        <div class="footer" onclick="$(this).parent().toggleClass('open')">
                            <i class="arrowCss down"></i>
                        </div>
                    </div>
                </div>
            @endif

            <div id="commentSection" class="commentSection">
                <div class="headerWithLine">
                    <div class="headerWithLineText">
                        نظرها
                    </div>
                </div>
                @include('component.commentingSection')
                <script>
                    commentingInitdata = { 'videoId': {{$video->id}} };
                    let videoComments = {!! $video->comments !!};

                    initCommentingSection(commentingInitdata);
                    fillMainCommentSection(videoComments);
                </script>
            </div>

            <div class="otherSection">
                <div class="headerWithLine">
                    <div class="headerWithLineText">
                        شاید جالب باشد
                    </div>
                </div>

                <div class="otherSectionBody">

                    <div class="videoSuggestionSwiper swiper-container">

                        <div id="maybeInterestedVideo" class="swiper-wrapper">
                            {{--fill with js videoSuggestion()--}}
                        </div>

                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

	<script src="https://vjs.zencdn.net/5.19.2/video.js"></script>
	<script src="{{URL::asset('js/video/hls.min.js?v=v0.9.1')}}"></script>
    <script src="{{URL::asset('js/video/videojs5-hlsjs-source-handler.min.js?v=0.3.1')}}"></script>
    <script src="{{URL::asset('js/video/vjs-quality-picker.js?v=v0.0.2')}}"></script>

    <script>
        var player = videojs('video_1');

        player.qualityPickerPlugin();

        @if($isLink)
            player.src({ src: '{{$video->link}}', type: 'application/x-mpegURL' });
        @else
            player.src({ src: '{{$video->link}}'});
        @endif

    </script>

    <script src="{{URL::asset('js/default/autosize.min.js')}}"></script>
    <script>
        let video = {!! $video !!};
        let uLike = {{$video->uLike}};
        let userMoreVideo = [];
        let sameCategory = [];

        let nonPic = '{{URL::asset('_images/nopic/blank.jpg')}}';
        $(window).ready(function(){
            autosize($('textarea'));
        });

        @if(isset($sameCategory) && count($sameCategory) > 0)
            sameCategory = {!! $sameCategory !!};
        createVideoSuggestionDiv(sameCategory, 'maybeInterestedVideo');
        @endif

            @if(isset($userMoreVideo) && count($userMoreVideo) > 0)
            userMoreVideo = {!! $userMoreVideo !!}
        createVideoSuggestionDiv(userMoreVideo, 'videoThisVideo', function(){
            $('#videoThisVideo').find('.videoSuggestion').addClass('sideSuggestion');
            resizeFitImg('resizeImgClass');
        });
            @endif


        var swiper = new Swiper('.videoSuggestionSwiper', {
                slidesPerGroup: 1,
                spaceBetween: 5,
                watchOverflow: true,
                navigation: {
                    nextEl: '.swiper-button-prev',
                    prevEl: '.swiper-button-next',
                },
                breakpoints: {
                    700: {
                        slidesPerView: 2,
                    },
                    900: {
                        slidesPerView: 3,
                    },
                    1200: {
                        slidesPerView: 4,
                    },
                    10000: {
                        slidesPerView: 5,
                    }
                },
                on: {
                    init: function () {

                        let slideCount = this.slides.length;
                        if(slideCount <= this.params.slidesPerView){
                            $(this.el).find(this.params.navigation.nextEl).css('display', 'none');
                            $(this.el).find(this.params.navigation.prevEl).css('display', 'none');
                        }
                        else{
                            $(this.el).find(this.params.navigation.nextEl).css('display', 'block');
                            $(this.el).find(this.params.navigation.prevEl).css('display', 'block');
                        }

                        $(this.el).find(this.params.navigation.prevEl).css('display', 'none');
                    },
                    resize: function(){
                        let slideCount = this.slides.length;
                        if(slideCount <= this.params.slidesPerView){
                            $(this.el).find(this.params.navigation.nextEl).css('display', 'none');
                            $(this.el).find(this.params.navigation.prevEl).css('display', 'none');
                        }
                        else{
                            $(this.el).find(this.params.navigation.nextEl).css('display', 'block');
                            $(this.el).find(this.params.navigation.prevEl).css('display', 'block');
                        }

                        resizeFitImg('resizeImgClass');
                    },
                    slideChange: function(){
                        if(this.isBeginning)
                            $(this.el).find(this.params.navigation.prevEl).css('display', 'none');
                        else
                            $(this.el).find(this.params.navigation.prevEl).css('display', 'block');

                        if(this.isEnd)
                            $(this.el).find(this.params.navigation.nextEl).css('display', 'none');
                        else
                            $(this.el).find(this.params.navigation.nextEl).css('display', 'block');
                    }
                },
            });

        function setFeedback(_value){
            if (!hasLogin) {
                showLoginPrompt();
                return;
            }

            if(_value == uLike)
                _value = 0;

            $.ajax({
                type: 'post',
                url: '{{route("video.setVideoFeedback")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    kind: 'likeVideo',
                    videoId: video.id,
                    like: _value
                },
                success: function(response){
                    if(response.status == 'ok') {
                        $('.likeVideoButton').removeClass('fill');
                        $('.disLikeVideoButton').removeClass('fill');

                        if (_value == 1)
                            $('.likeVideoButton').toggleClass('fill');
                        else if (_value == -1)
                            $('.disLikeVideoButton').toggleClass('fill');

                        $('.likeVideoButton').text(response.like);
                        $('.disLikeVideoButton').text(response.disLike);
                        uLike = _value;
                    }
                }
            })
        }

        function goToComments(){
            $('html, body').animate({
                scrollTop: $('#commentSection').offset().top - 100
            }, 800);

            if (!hasLogin) {
                showLoginPrompt();
                return;
            }
        }

        function openMorePlace(_element){
            let notShowPlace = $(_element).parent().find('.notShowPlace');

            if(notShowPlace.css('display') == 'none'){
                notShowPlace.css('display', 'flex');
                $(_element).text('کمتر');
            }
            else{
                notShowPlace.css('display', 'none');
                $(_element).text('بیشتر');
            }
        }

        @if($video->hasPlace)
            function getVideoPlaces(){
                $.ajax({
                    type: 'get',
                    url: '{{route("ajax.getVideoPlaces").'?code='.$video->code}}',
                    success: response => {
                        if(response.status == 'ok'){
                            var text = '';
                            response.result.state.map(item => text += createPlaceRelatedHtml(`استان ${item.name}`, item.url, item.pic));
                            response.result.cities.map(item => {
                                footer = `استان ${item.state}`;
                                text += createPlaceRelatedHtml(`شهر ${item.name}`, item.url, item.pic, footer)
                            });
                            response.result.places.map(item => {
                                footer = `استان ${item.state} ، شهر ${item.city}`;
                                text += createPlaceRelatedHtml(item.name, item.url, item.pic, footer)
                            });

                            $('.relatedPlaceBody').html(text);
                        }
                        else{
                            console.log(response.result);
                            $('.placeRelatedSide').remove();
                        }
                    },
                    error: err => {
                        console.log(err);
                        $('.placeRelatedSide').remove();
                    }
                });
            }

            function createPlaceRelatedHtml(_name, _url, _pic, _footer = ''){
                return `<a href="${_url}" class="item">
                            <div class="pic">
                                <img src="${_pic}" class="resizeImgClass" style="width: 100%" onload="resizeThisImg(this)">
                            </div>
                            <div class="infos">
                                <div class="name">${_name}</div>
                                <div class="category">${_footer}</div>
                            </div>
                        </a>`;

            }
            getVideoPlaces();
        @endif
    </script>
@endsection
