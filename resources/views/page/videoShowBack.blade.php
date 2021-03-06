@extends('layout.mainLayout')

@section('head')
    <title> کوچیتا تی وی </title>
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:type" content="website" />
    <meta name="title" content="کوچیتا | سامانه جامع گردشگری ایران و شبکه اجتماعی گردشگران" />
    <meta name='description' content='کوچیتا، سامانه جامع گردشگری ایران و شبکه اجتماعی گردشگران. اطلاعات اماکن و جاذبه ها، هتل ها، بوم گردی، ماجراجویی، آموزش سفر، فروشگاه صنایع‌دستی ، پادکست سفر' />
    <meta name='keywords' content='کوچیتا، هتل، تور ، سفر ارزان، سفر در ایران، بلیط، تریپ، نقد و بررسی، سفرنامه، کمپینگ، ایران گردی، آموزش سفر، مجله گردشگری، مسافرت، مسافرت داخلی, ارزانترین قیمت هتل ، مقایسه قیمت ، بهترین رستوران‌ها ، بلیط ارزان ، تقویم تعطیلات' />
    <meta property="og:image" content="{{ asset('images/blank.jpg') }}"/>
    <meta property="og:image:secure_url" content="{{ asset('images/blank.jpg') }}"/>
    <meta property="og:image:width" content="550"/>
    <meta property="og:image:height" content="367"/>
    <meta name="twitter:image" content="{{ asset('images/blank.jpg') }}"/>

    <link rel="stylesheet" href="{{URL::asset('css/pages/videoShow.css')}}">

    <link href="https://vjs.zencdn.net/5.19.2/video-js.css" rel="stylesheet">
    <style type="text/css">
        .video-js {
            font-size: 1rem;
        }
    </style>

    <link href="https://vjs.zencdn.net/7.7.5/video-js.css" rel="stylesheet" />

    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src="https://vjs.zencdn.net/7.7.5/video.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.15.0/videojs-contrib-hls.min.js"></script>

    <style>
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
        }
    </style>
@endsection

@section('body')

    <div style="width: 99%; display: flex">
        <div class="container mainShowBase hideOnTablet" style="width: 24%">

            <div class="videoInfos">
                <div class="videoInfosVideoName">
                    {{$video->title}}
                </div>
                <div class="row mainUserPicSection">
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

            @if($video->description != null && $video->description != '')
                <div class="descriptionSection">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            معرفی کلی
                        </div>
                        <div class="headerWithLineLine"></div>
                    </div>
                    <div class="descriptionSectionBody">
                        {!! $video->description !!}
                    </div>
                </div>
            @endif

            @if(isset($video->places) && count($video->places) > 0)
                <div class="moreInfoMainDiv">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            اطلاعات بیشتر
                        </div>
                        <div class="headerWithLineLine"></div>
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
                        <div class="headerWithLineLine"></div>
                    </div>
                    <div id="videoThisVideo" style="display: flex; align-items: center; flex-wrap: wrap; justify-content: space-evenly"></div>
                </div>
            @endif

        </div>
        <div class="container mainShowBase">
            <div class="showVideo">
                <video id="video_1" class="video-js playads" controls style="width: 100%" data-setup='{"fluid": true, "preload": "none", "auto-play": false }'></video>
                <script>
                    var myPlayer = videojs('video_1', {autoplay: 'any'});
                    myPlayer.src({
                        src: '{{$video->video}}',
                        // type: 'application/x-mpegURL',
                        withCredentials: false
                    });
                </script>
{{--                <video src="{{$video->video}}" poster="{{$video->pic}}" style="width: 100%" controls playsinline>--}}
{{--                    <source src="{{$video->video}}" type="video/mp4">--}}
{{--                </video>--}}
            </div>

            <div class="toolSection">
                <div class="toolSectionButtons">
                    <div class="toolSectionButtonsCircle" onclick="setFeedback('like', -1)">
                        <span id="videoDisLikeIcon" class="DisLikeIcon {{$video->uLike == -1 ? 'fullDisLikeColor' : ''}}"></span>
                    </div>
                    <div class="toolSectionButtonsCircle" onclick="setFeedback('like', 1)">
                        <span id="videoLikeIcon" class="LikeIcon {{$video->uLike == 1 ? 'fullLikeColor' : ''}}"></span>
                    </div>
                    <div class="toolSectionButtonsCircle" onclick="goToComments()">
                        <span class="CommentIcon CommentIconSett"></span>
                    </div>
                    <div id="share_pic" class="toolSectionButtonsCircle share_pic">
                        <span class="ShareIcon ShareIconSett"></span>
                        @include('component.shareBox')
                    </div>
{{--                    <div class="toolSectionButtonsCircle">--}}
{{--                        <span class="HeartIcon HeartIconSett"></span>--}}
{{--                    </div>--}}
{{--                    <div class="toolSectionButtonsCircle">--}}
{{--                        <span class="BookMarkIcon BookMarkIconSett"></span>--}}
{{--                    </div>--}}
                </div>
                <div class="toolSectionInfos">
                    <div class="toolSectionInfosTab">
                        <span class="CommentIcon commentInfoTab"></span>
                        <span id="commentCount" class="toolSectionInfosTabNumber">{{$video->commentsCount}}</span>
                    </div>
                    <div class="toolSectionInfosTab">
                        <span class="LikeIcon likeInfoTab"></span>
                        <span id="likeCount" class="toolSectionInfosTabNumber">{{$video->like}}</span>
                    </div>
                    <div class="toolSectionInfosTab">
                        <span class="DisLikeIcon disLikeInfoTab"></span>
                        <span id="disLikeCount" class="toolSectionInfosTabNumber">{{$video->disLike}}</span>
                    </div>
                    <div class="toolSectionInfosTab">
                        <img src="{{URL::asset('images/mainPics/eye.png')}}" class="eyeClass" style="width: 25px">
                        <span class="toolSectionInfosTabNumber">{{$video->seen}}</span>
                    </div>
                </div>
            </div>

            <div class="showInPhone">
                <div class="videoInfos" style="width: 100%">
                    <div class="videoInfosVideoName">
                        {{$video->title}}
                    </div>
                    <div class="row mainUserPicSection">
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

                @if($video->description != null && $video->description != '')
                    <div class="descriptionSection" style="width: 100%">
                        <div class="headerWithLine">
                            <div class="headerWithLineText">
                                معرفی کلی
                            </div>
                            <div class="headerWithLineLine"></div>
                        </div>
                        <div class="descriptionSectionBody">
                            {!! $video->description !!}
                        </div>
                    </div>
                @endif

                @if(isset($video->places) && count($video->places) > 0)
                    <div class="moreInfoMainDiv" style="width: 100%">
                        <div class="headerWithLine">
                            <div class="headerWithLineText">
                                اطلاعات بیشتر
                            </div>
                            <div class="headerWithLineLine"></div>
                        </div>
                        <div id="mobileVideoPlace" class="videoPlaces">
                            @for($i = 0; $i < count($video->places); $i++)
                                <div class="moreInfoEachItem {{$i >= 5 ? 'notShowPlace' : ''}}">
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

                            @if(count($video->places) > 5)
                                <div id="mobileMoreBtn" class="moreBtn" onclick="openMorePlace(this)">بیشتر</div>
                            @endif

                        </div>
                    </div>
                @endif
            </div>

            <div id="commentSection" class="commentSection">
                <div class="headerWithLine">
                    <div class="headerWithLineText">
                        نظرها
                    </div>
                    <div class="headerWithLineLine"></div>
                </div>
                @include('component.commentingSection')
                <script>
                    commentingInitdata = {
                        'videoId': {{$video->id}}
                    };
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
                    <div class="headerWithLineLine"></div>
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

        player.src({
            src: '{{$video->link}}',
            type: 'application/x-mpegURL'
        });

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

        function setFeedback(_kind, _value){
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
                    response = JSON.parse(response);
                    if(response['status'] == 'ok'){
                        uLike = _value;

                        $('#likeCount').text(response.like);
                        $('#disLikeCount').text(response.disLike);

                        $('#videoDisLikeIcon').removeClass('fullDisLikeColor');
                        $('#videoLikeIcon').removeClass('fullLikeColor');

                        if(_value == 1)
                            $('#videoLikeIcon').addClass('fullLikeColor');
                        else if(_value == -1)
                            $('#videoDisLikeIcon').addClass('fullDisLikeColor');
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
            // if($(_element).next().css('display') == 'none') {
            //     $(_element).next().css('display', 'flex');
            //     $(_element).text('کمتر');
            // }
            // else {
            //     $(_element).next().css('display', 'none');
            //     $(_element).text('بیشتر');
            // }
        }
        // resizeFitImg('resizeImgClass');
    </script>
@endsection
