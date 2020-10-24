@extends('layout.mainLayout')


@section('head')
    <link rel="stylesheet" type='text/css' href='{{URL::asset('css/pages/mainPage.css')}}'>
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
{{--                    <div class="swiper-slide mobileHeight imgOfSliderBox" style="overflow: hidden">--}}
{{--                        <img src="{{URL::asset('images/mainPics/liveBanner.jpg')}}" class="resizeImgClass">--}}
{{--                        <div class="nowSeeThisVideoDiv">--}}
{{--                            <img src="{{URL::asset('images/mainPics/playb.png')}}" class="nowSeeThisVideoButtonImage">--}}
{{--                            <a href="#" class="nowSeeThisVideoButton">--}}
{{--                                همین حالا ببینید--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div class="nowSeeThisVideoNameDiv">--}}
{{--                            <a href="#" class="nowSeeThisVideoName">--}}
{{--                                گفتگوی زنده--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="swiper-pagination"></div>--}}
{{--                <div class="swiper-button-next"></div>--}}
{{--                <div class="swiper-button-prev"></div>--}}
            </div>


        </div>
        <div class="pushTopMainSlider"></div>

        <div class="otherSection">
            <div class="headerWithLine">
                <div class="headerWithLineText">
                   آخرین ویدیو ها
                </div>
                <div class="headerWithLineLine"></div>

            </div>
            <div class="otherSectionBody">
                <div class="videoSuggestionSwiper swiper-container">

                    <div id="lastVideosDiv" class="swiper-wrapper">
{{--                        fill with js lastVideoSuggestion()--}}
{{--                        streaming.videoSuggestion--}}

                        {{--this bellow code only for not empty in begining and auto deleted--}}
                        <div class="swiper-slide videoSuggestion">
                            <div class="videoSuggPlaceHolderDiv" style=" width: 100%;">
                                <div class="videoSugPicSection placeHolderAnime"></div>
                                <div class="videoSugInfo">
                                    <div class="videoSugUserInfo videoSugUserInfoPlaceHolder">
                                        <div class="videoSugName videoSuggNamePlaceHolder placeHolderAnime" style="height: 15px"></div>
                                    </div>

                                    <div class="videoSugUserPic">
                                        <div class="videoSugUserPicDiv placeHolderAnime"></div>
                                        <div class="videoUserInfoName">
                                            <div class="videoSugUserName videoSuggNamePlaceHolder placeHolderAnime" style="margin-bottom: 5px"></div>
                                            <div class="videoSugTime videoSuggNamePlaceHolder placeHolderAnime"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <div class="headerWithLineLine"></div>
            </div>
            <div class="otherSectionBody">
                <div class="videoSuggestionSwiper swiper-container">

                    <div id="topVideosDiv" class="swiper-wrapper">
                        {{--fill with js createTopVideoSuggestion()--}}
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
                        <div class="headerWithLineLine"></div>

                        <a href="{{route('video.list', ['kind' => 'category', 'value' => $cat->id])}}" class="allVideoButton">
                            مشاهده همه
                        </a>
                    </div>
                    <div class="otherSectionBody">
                        <div class="videoSuggestionSwiper swiper-container">

                            <div id="catVideoDiv_{{$cat->id}}" class="swiper-wrapper">
                                {{--fill with js topVideoSuggenstion()--}}

                                {{--this bellow code only for not empty in begining and auto deleted--}}
                                <div class="swiper-slide videoSuggestion">
                                    <div class="videoSuggPlaceHolderDiv" style=" width: 100%;">
                                        <div class="videoSugPicSection placeHolderAnime"></div>
                                        <div class="videoSugInfo">
                                            <div class="videoSugUserInfo videoSugUserInfoPlaceHolder">
                                                <div class="videoSugName videoSuggNamePlaceHolder placeHolderAnime" style="height: 15px"></div>
                                            </div>

                                            <div class="videoSugUserPic">
                                                <div class="videoSugUserPicDiv placeHolderAnime"></div>
                                                <div class="videoUserInfoName">
                                                    <div class="videoSugUserName videoSuggNamePlaceHolder placeHolderAnime" style="margin-bottom: 5px"></div>
                                                    <div class="videoSugTime videoSuggNamePlaceHolder placeHolderAnime"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>

@endsection

@section('script')
    <!-- Swiper JS -->

    <script>
        let lastVideos = {!! $lastVideos !!};
        let videoCategory = {!! $videoCategory !!};
        let topVideos = {!! $topVideos !!};

        createVideoSuggestionDiv(lastVideos, 'lastVideosDiv');

        function categoryVideoSuggestion(){
            for(let j = 0; j < videoCategory.length; j++)
                createVideoSuggestionDiv(videoCategory[j].video, 'catVideoDiv_' + videoCategory[j]['id']);
        }
        categoryVideoSuggestion();

        function createTopVideoSuggestion(){
            createVideoSuggestionDiv(topVideos, 'topVideosDiv')
        }
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

        let mobileVideo = "{{URL::asset('images/tv_mobile.mp4')}}";
        let pcVideo = "{{URL::asset('images/comp2.mp4')}}";
        $(window).on('resize', changeVideoSource);

        function changeVideoSource(){
            let currentTime = document.getElementById('mainVideo').currentTime;
            if($(this).width() < 771){
                if($('#mainVideo').attr('src') != mobileVideo)
                    $('#mainVideo').attr('src', mobileVideo);

                if($(this).width() < 500){
                    $('#mainVideo').css('height', '100%');
                    $('#mainVideo').css('width', 'auto');
                }
                else{
                    $('#mainVideo').css('width', '100%');
                    $('#mainVideo').css('height', 'auto');
                }
            }
            else{
                if($('#mainVideo').attr('src') != pcVideo)
                    $('#mainVideo').attr('src', pcVideo);

                $('#mainVideo').css('height', '100%');
                $('#mainVideo').css('width', 'auto');
            }
            document.getElementById('mainVideo').currentTime = currentTime;
        }

        $(document).ready(changeVideoSource);
    </script>
@endsection
