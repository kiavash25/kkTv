<style>

    .userIntroRow{
        padding: 0px 0px;
        display: flex;
        max-height: 230px;
        overflow: hidden;
    }
    .userIntroRow .videoSec{
        width: 30%;
    }
    .userIntroRow .infoSec{
        width: 69%;
        color: white;
        padding: 5px 15px;
        display: flex;
        flex-direction: column;
    }
    .userIntroRow .infoSec .name{
        font-size: 20px;
    }
    .userIntroRow .infoSec .description{
        margin-top: 10px;
        color: #868686;
        font-size: 15px;
    }
</style>

<div class="userIntroRow">
    <div class="videoSec">
        <video src="http://localhost/assets/_images/video/3/1603817656_3.mp4" controls style="width: 100%; max-height: 230px;"></video>
    </div>
    <div class="infoSec">
        <a href="#" class="name">محمد علی کشاورز</a>
        <div class="description">
            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد وزمان مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.
        </div>
    </div>
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

    <div class="col-md-12">
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
    var userCategories = {!! $userCategories !!};
    var userTenLastVideo = {!! $lastVideos !!};
    var tenPlayList = [];
    {!! json_encode($playListsVideos) !!}.map(item => tenPlayList.push(item));

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

</script>
