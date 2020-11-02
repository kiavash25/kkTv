
@if($yourPage == 1)
    <button class="bigBlueButtonNonCorner plusIcon" onclick="openChooseTopVideoModal()"> {{$topVideo == null ? 'انتخاب' : 'ویرایش'}} ویدیو شاخص</button>
@endif
<div id="topVideoSectionProfile" data-value="{{$topVideo == null ? 0 : $topVideo->id}}" class="profilePage userIntroRow {{$topVideo == null ? 'hidden' : ''}}">
    <div class="videoSec">
        <video id="topVideoVideoTag"
               src="{{$topVideo == null ? '#' : $topVideo->videoUrl}}"
               controls
               poster="{{$topVideo == null ? '' : $topVideo->pic}}"
               style="width: 100%; direction: ltr; max-height: 230px;"></video>
    </div>
    <div class="infoSec">
        <a href="{{$topVideo == null ? '#' : $topVideo->url}}" class="name">
            {{$topVideo == null ? '' : $topVideo->title}}
        </a>
        <div class="description">
            {{$topVideo == null ? '' : $topVideo->description}}
        </div>
    </div>
    @if($topVideo != null && $topVideo->link != null)
        <script>
            $('#topVideoVideoTag').addClass('playads embed-responsive-item video-js vjs-default-skin vjs-16-9');
            $('#topVideoVideoTag').attr('data-setup', '{"fluid": true, "preload": "none", "auto-play": false }');

            var player = videojs('video_1');
            player.qualityPickerPlugin();
            player.src({ src: '{{$topVideo->link}}', type: 'application/x-mpegURL' });
        </script>
    @endif
</div>

<div class="row changeHeaderColor" style="width: 100%">
    <div class="col-md-12">
        <div class="headerWithLine">
            <div class="headerWithLineText">
                آخرین ویدیو ها
            </div>
        </div>

        <div class="otherSectionBody">
            <div class="videoSuggestionSwiper swiper-container">

                <div id="lastVideosDiv" class="swiper-wrapper">
                    <div class="videoSuggestion"></div>
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

    </div>

    <div id="homePlayListSection" class="col-md-12 {{count($playListsVideos) == 0 ? 'hidden' : ''}}">
        <div class="headerWithLine">
            <div class="headerWithLineText">
                لیست پخش
            </div>
        </div>
        <div class="otherSectionBody">
            <div class="videoSuggestionSwiper swiper-container">

                <div id="videoPlayListRow" class="swiper-wrapper">
                    <div class="videoSuggestion"></div>
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>

    @foreach($userCategories as $uCat)
        <div class="col-md-12">
            <div class="headerWithLine">
                <div class="headerWithLineText">{{$uCat->name}}</div>
            </div>

            <div class="otherSectionBody">
                <div class="videoSuggestionSwiper swiper-container">

                    <div id="userVideoCatRow_{{$uCat->id}}" class="swiper-wrapper">
                        <div class="videoSuggestion"></div>
                    </div>

                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

        </div>
    @endforeach


</div>

<script>
    var homeShowPlayList = {!! json_encode($playListsVideos) !!};
    var userCategories = {!! $userCategories !!};
    var userTenLastVideo = {!! $lastVideos !!};
    var tenPlayList = [];
    homeShowPlayList.map(item => tenPlayList.push(item));

    $('.videoSuggestion').html(returnVideoSuggPlaceHolder() /**in videoSuggestionPack.blade.php**/);
    createVideoSuggestionDiv(userTenLastVideo, 'lastVideosDiv') /**in videoSuggestionPack.blade.php**/;
    userCategories.map(item => createVideoSuggestionDiv(item.vid, `userVideoCatRow_${item.id}`));/**in videoSuggestionPack.blade.php**/

    $('#videoPlayListRow').html(createPlayListObjGroups(tenPlayList) /**in playListObj.blade.php**/);

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

    openChooseTopVideoModal = () => openAllVideoSelectModal('انتخاب ویدیوی شاخص',
                                                            showTopVideo,
                                                            'single',
                                                            [$('#topVideoSectionProfile').attr('data-value')]);

    function showTopVideo(_id) {
        _id = _id[0];

        var videoIndex = null;
        for(var i = 0; i < allUserVideos.length; i++){
            if(allUserVideos[i].id == _id){
                videoIndex = i;
                break;
            }
        }

        if(videoIndex != null) {
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('profile.updateTopVideo')}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: allUserVideos[videoIndex].id
                },
                success: response =>{
                    closeLoading();
                    if(response.status == 'ok'){
                        allUserVideos.map(item =>item.isTopVideo = 0);
                        allUserVideos[videoIndex].isTopVideo = 1;
                        var video = allUserVideos[videoIndex];

                        $('#topVideoSectionProfile').removeClass('hidden');
                        $('#topVideoSectionProfile').find('video').attr('poster', video.pic);
                        $('#topVideoSectionProfile').find('a').attr('href', video.url);
                        $('#topVideoSectionProfile').find('a').text(video.title);
                        $('#topVideoSectionProfile').find('.description').text(video.description);

                        $('#topVideoSectionProfile').find('video').attr('src', video.videoUrl);

                        if(video.link == null){
                            $('#topVideoVideoTag').removeClass('playads embed-responsive-item video-js vjs-default-skin vjs-16-9');
                            $('#topVideoVideoTag').attr('data-setup', '');
                        }
                        else{
                            $('#topVideoVideoTag').addClass('playads embed-responsive-item video-js vjs-default-skin vjs-16-9');
                            $('#topVideoVideoTag').attr('data-setup', '{"fluid": true, "preload": "none", "auto-play": false }');

                            var player = videojs('video_1');
                            player.qualityPickerPlugin();
                            player.src({ src: video.link, type: 'application/x-mpegURL' });
                        }

                        showSuccessNotifi('ویدویوی شاخص با موفقیت به روزرسانی شد', 'left', 'var(--koochita-blue)');
                    }
                    else
                        showSuccessNotifi('مشکلی در بروز رسانی پیش امده', 'left', 'red');
                },
                error: err => {
                    closeLoading();
                    console.log(err);
                    showSuccessNotifi('مشکلی در بروز رسانی پیش امده', 'left', 'red');
                }
            })
        }
    }
</script>
