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

    <link rel="stylesheet" type='text/css' href='{{URL::asset('css/pages/mainPage.css?v='.$fileVersion)}}'>
    <style>


        .commonSoundIcon{
            position: absolute;
            left: 10px;
            bottom: 10px;
            color: white;
            font-size: 50px;
            line-height: 50px;
            border: solid 3px;
            border-radius: 50%;
            cursor: pointer;
        }
        .playSliderIcon{
            position: absolute;
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
            background: #00000066;
            display: none;
            flex-direction: column;
        }
        .playSliderIcon img{
            width: 80px;
            cursor: pointer;
            transition: .3s;
        }
        .playSliderIcon:hover img{
            transform: scale(1.1);
        }
        @media (max-width: 771px) {
            .commonSoundIcon{
                font-size: 25px;
                line-height: 25px;
            }
        }
    </style>
@endsection

@section('body')

    <div class="container mainShowBase">
        <div class="mainSlider">
            <div id="mainSlider" class="swiper-container backgroundColorForSlider" style="display: flex; justify-content: center; align-items: center;">
                <video id="mainVideo" src="#" autoplay muted loop></video>
                <div class="commonSoundIcon soundIcon" style="display: none" onclick="toggleVideoSound(0, this)"></div>
                <div class="commonSoundIcon muteIcon" onclick="toggleVideoSound(1, this)"></div>

{{--                <div class="swiper-wrapper">--}}
{{--                    <div class="swiper-slide mobileHeight imgOfSliderBox" style="overflow: hidden; justify-content: start;">--}}
{{--                        <img src="{{URL::asset('images/notImportant/banner.jpg')}}" class="resizeImgClass" onload="showThisVideoSugg(this)" style="width: 100%">--}}
{{--                        <div class="nowSeeThisVideoDiv" style="color: white; font-size: 35px; flex-direction: column; align-items: flex-end;">--}}
{{--                            <div id="timeToStart"></div>--}}
{{--                            <div style="color: #f4c15b; font-size: 19px;">مانده به شروع پخش زنده</div>--}}
{{--                        </div>--}}
{{--                        <a id="playSliderIcon" href="{{route('streaming.live', ['room' => $timeToLiveCode])}}" class="playSliderIcon">--}}
{{--                            <img src="{{URL::asset('images/mainPics/play.png')}}">--}}
{{--                            <div style="color: white; font-size: 32px;">همین حالا ببینید</div>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="swiper-pagination"></div>--}}
{{--                <div class="swiper-button-next"></div>--}}
{{--                <div class="swiper-button-prev"></div>--}}
            </div>


            <script>
                {{--let countDownDate  = new Date("Oct 25, 2020 {{$timeToLive}}").getTime();--}}
                {{--var x = setInterval(function() {--}}
                {{--    var now = new Date().getTime();--}}
                {{--    var distance = countDownDate - now;--}}
                {{--    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));--}}
                {{--    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));--}}
                {{--    var seconds = Math.floor((distance % (1000 * 60)) / 1000);--}}

                {{--    if(hours < 10)--}}
                {{--        hours = '0'+hours;--}}
                {{--    if(minutes < 10)--}}
                {{--        minutes = '0'+minutes;--}}
                {{--    if(seconds < 10)--}}
                {{--        seconds = '0'+seconds;--}}

                {{--    document.getElementById("timeToStart").innerHTML = hours + ":" + minutes + ":" + seconds;--}}
                {{--    if (distance < 0) {--}}
                {{--        clearInterval(x);--}}
                {{--        $('#timeToStart').parent().hide();--}}
                {{--        $('#playSliderIcon').css('display', 'flex');--}}
                {{--    }--}}
                {{--}, 1000);--}}
            </script>


        </div>
        <div class="pushTopMainSlider"></div>

        <div class="otherSection">
            <div class="headerWithLine">
                <div class="headerWithLineText">
                   آخرین ویدیو ها
                </div>
            </div>
            <div class="otherSectionBody">
                <div class="videoSuggestionSwiper swiper-container">

                    <div id="lastVideosDiv" class="swiper-wrapper">
{{--                        fill with js lastVideoSuggestion()--}}
{{--                        component.videoSuggestionPack.blade.php--}}
                        <div class="videoSuggestion"></div>
                    </div>

                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>

        <div class="otherSection">
            <div class="headerWithLine">
                <div class="headerWithLineText">
                    محبوب ها
                </div>
            </div>
            <div class="otherSectionBody">
                <div class="videoSuggestionSwiper swiper-container">

                    <div id="topVideosDiv" class="swiper-wrapper">
                        {{--fill with js createTopVideoSuggestion()--}}
                        <div class="videoSuggestion"></div>
                    </div>

                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>

        @foreach($videoCategory as $cat)
            @if(count($cat->video) > 0)
                <div class="otherSection">
                    <div class="headerWithLine">
                        <div class="headerWithLineText">
                            {{$cat->name}}
                        </div>
                        <a href="{{route('video.list', ['kind' => 'category', 'value' => $cat->id])}}" class="allVideoButton">
                            مشاهده همه
                        </a>
                    </div>
                    <div class="otherSectionBody">
                        <div class="videoSuggestionSwiper swiper-container">

                            <div id="catVideoDiv_{{$cat->id}}" class="swiper-wrapper">
                                {{--fill with js topVideoSuggenstion()--}}
                                <div class="videoSuggestion"></div>
                            </div>

                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>


    @if(!$registerInCarpet)
    <style>
        .carpetModal .closeIcon:before{
            font-size: 60px;
            line-height: 68px;
            color: red;
            position: absolute;
            top: 0px;
            left: 0px;
            cursor: pointer;
        }
    </style>
    <div id="carpetMatchModal" class="modal carpetModal">
        <div class="modal-dialog" style="display: flex; justify-content: center; align-items: center;">
            <div class="closeIcon" onclick="closeCarpetModal()"></div>
            <img src="{{URL::asset('images/notImportant/carpetMatch.jpg')}}" alt="carpetMatch" style="height: 90vh; cursor:pointer;" onclick="registerInCarpet()">
        </div>
    </div>

    <script>
        $(window).ready(() => {
            $('#carpetMatchModal').modal('show');
        });

        function registerInCarpet(){
            @if(auth()->check())
                location.href = '{{route("registerInCarpetMatch")}}';
            @else
                checkLogin('{{route("registerInCarpetMatch")}}');
            @endif
        }

        function closeCarpetModal(){
            $('#carpetMatchModal').modal('hide');
        }
    </script>

    @endif

    <script >
        @if(session('msg'))
            @if(session('msg') == 'carpetRegister')
                showSuccessNotifi('ثبت نام شما در رویداد مجازی فرش با موفقیت انجام شد.', 'left', 'var(--koochita-blue)');
            @elseif(session('msg') == 'youHasIn')
                showSuccessNotifi('شما قبلا در رویداد مجازی فرش ثبت نام کرده اید.', 'left', 'var(--koochita-yellow)');
            @endif
        @endif
    </script>

@endsection

@section('script')

    <script>
        $('.videoSuggestion').html(returnVideoSuggPlaceHolder() /**in videoSuggestionPack.blade.php**/);

        let lastVideos = {!! $lastVideos !!};
        let videoCategory = {!! $videoCategory !!};
        let topVideos = {!! $topVideos !!};

        createVideoSuggestionDiv(lastVideos, 'lastVideosDiv');

        function categoryVideoSuggestion(){
            for(let j = 0; j < videoCategory.length; j++)
                createVideoSuggestionDiv(videoCategory[j].video, 'catVideoDiv_' + videoCategory[j]['id']);
        }
        categoryVideoSuggestion();

        const createTopVideoSuggestion = () => createVideoSuggestionDiv(topVideos, 'topVideosDiv');
        createTopVideoSuggestion();

        var swipersuggestion = new Swiper('.videoSuggestionSwiper', {
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

        // mainSlider
        // new Swiper('#mainSlider', {
        //     spaceBetween: 30,
        //     centeredSlides: true,
        //     loop: true,
        //     autoplay: {
        //         delay: 50000,
        //         disableOnInteraction: false,
        //     },
        //     pagination: {
        //         el: '.swiper-pagination',
        //         clickable: true,
        //     },
        //     navigation: {
        //         nextEl: '.swiper-button-next',
        //         prevEl: '.swiper-button-prev',
        //     },
        // });

        function toggleVideoSound(_kind, _element){
            $(_element).hide();
            if(_kind == 1){
                $('#mainVideo').prop('muted', false);
                $(_element).prev().show();
            }
            else{
                $('#mainVideo').prop('muted', true);
                $(_element).next().show();
            }
        }

{{--        let mobileVideo = "{{URL::asset('images/tv_mobile.mp4')}}";--}}
        let mobileVideo = "{{URL::asset('images/video/comp3.mp4')}}";
        let pcVideo = "{{URL::asset('images/video/comp3.mp4')}}";
        $(window).on('resize', changeVideoSource);

        function changeVideoSource(){
            let currentTime = document.getElementById('mainVideo').currentTime;
            if($(this).width() < 771){
                if($('#mainVideo').attr('src') != mobileVideo)
                    $('#mainVideo').attr('src', mobileVideo);

                if($(this).width() < 500)
                    $('#mainVideo').css({height: '100%', width: 'auto'});
                else
                    $('#mainVideo').css({height: 'auto', width: '100%'});
            }
            else{
                if($('#mainVideo').attr('src') != pcVideo)
                    $('#mainVideo').attr('src', pcVideo);

                $('#mainVideo').css({height: '100%', width: 'auto'});
            }
            document.getElementById('mainVideo').currentTime = currentTime;
        }

        $(document).ready(changeVideoSource);
    </script>
@endsection
