@extends('layout.mainLayout')


@section('head')

    <link rel="stylesheet" href="{{ asset('css/indexStreaming.css') }}">
@endsection

@section('body')

    <div class="container mainShowBase">

        <div class="mainSlider">
            <div id="mainSlider" class="swiper-container backgroundColorForSlider">
                <div class="swiper-wrapper">

                    <div class="swiper-slide mobileHeight imgOfSliderBox">
                        <img src="{{URL::asset('images/mainPics/liveBanner.jpg')}}" class="resizeImgClass">
                        <div class="nowSeeThisVideoDiv">
                            <img src="{{URL::asset('images/mainPics/playb.png')}}" class="nowSeeThisVideoButtonImage">
                            <a href="#" class="nowSeeThisVideoButton">
                                همین حالا ببینید
                            </a>
                        </div>
                        <div class="nowSeeThisVideoNameDiv">
                            <a href="#" class="nowSeeThisVideoName">
                                گفتگوی زنده
                            </a>
                        </div>
                    </div>

                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
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
                        {{--fill with js topVideoSuggenstion()--}}
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

        createVideoSuggestionDiv(lastVideos, 'lastVideosDiv');

        function categoryVideoSuggestion(){
            for(let j = 0; j < videoCategory.length; j++)
                createVideoSuggestionDiv(videoCategory[j].video, 'catVideoDiv_' + videoCategory[j]['id']);
        }
        categoryVideoSuggestion();

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
        new Swiper('#mainSlider', {
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 50000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
@endsection
