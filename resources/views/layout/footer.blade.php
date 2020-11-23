<?php
if(auth()->check()){
    $user = Auth::user();
//    $userLevelFooter = auth()->user()->getUserNearestLevel();
//    $userTotalPointFooter = auth()->user()->getUserTotalPoint();
//    $nextLevelFooter = $userLevelFooter[1]->floor - $userTotalPointFooter;

    $userLevelFooter = 0;
    $userTotalPointFooter = 0;
    $nextLevelFooter = 0;
}
?>
<link rel='stylesheet' type='text/css' href='{{URL::asset('css/layout/vodFooter.css')}}' />

<div class="clear-both" style="height: 75px"></div>

<footer>
    <div class="hideOnPhone screenFooterStyle">
        <div class="footerLogoSocialBox">
            <a href="{{route('index')}}" class="footerLogo">
                <img src="{{URL::asset('images/mainPics/vodLobo.png')}}" class="content-icon" width="100%">
            </a>
            <div class="footerSocialMediaBox">
                <a rel="nofollow" target="_blank" href="#">
                    <div class="footerIconHor linkedin"></div>
                </a>
                <a rel="nofollow" target="_blank" href="https://www.facebook.com/profile.php?id=100016313805277&ref=br_rs">
                    <div class="footerIconHor facebook"></div>
                </a>
                <a rel="nofollow" target="_blank" href="#">
                    <div class="footerIconHor twitter"></div>
                </a>
                <a rel="nofollow" target="_blank" href="https://www.instagram.com/koochita_com/">
                    <div class="footerIconHor instagram"></div>
                </a>
                <a class="socialLink" rel="nofollow"target="_blank" href="#">
                    <div class="footerIconHor telegram"></div>
                </a>
{{--                <a class="socialLink" rel="nofollow" target="_blank" href="">--}}
{{--                    <div class="footerIconHor aparat"></div>--}}
{{--                </a>--}}
            </div>
        </div>
        {{--        aboutShazdeMoreLess--}}
        <div style="display: flex">
            <div class="footerRside">
                <div id="aboutShazde" class="aboutShazdeMoreLess">
                    <div class="clear-both hideOnScreen"></div>
                    <div>کوچیتا تی وی، سرویس VOD اختصاصی گردشگری در ایران، برای تماشای آنلاین و زنده محتواهای بصری و صوتی در تمامی حوزه‌های گردشگری و سفر می‌باشد. در این سرویس تمام مخاطبان، سفردوستان، دارندگان کسب و کارهای مرتبط با سفر، می‌توانند ویدیوهای خود را آپلود کرده و با مخاطبان خود در ارتباط باشند.
                        تلویزیون کوچیتا با ارائه محتواهای آموزشی و تفریحی در حوزه تخصصی سفر و گردشگری، تنها منبع جامع در ایران می‌باشد.
                        شما با تماشای تلویزیون کوچیتا، می‌توانید به صورت تخصصی و با بالاترین کیفیت در حوزه‌هایی مثل ایرانگردی، جهان‌گردی، سفر ماجراجویانه، رکورد، سفرهای زیارتی،پیاده‌روی، سافاری، رفتینگ، کمپینگ، کوهنوردی، بوم‌‌گردی، اطلاعات جامع را کسب کنید.</div>
                </div>
                <div class="footMoreLessBtn" onclick="showMorefooter()">
                    <span class="footMoreLessBtnText">نمایش بیشتر</span>
                    <span class="footMoreLessBtnText hidden">نمایش کمتر</span>
                </div>
                <div class="aboutShazdeLinkMargin">
                    <div class="aboutShazdeLink" style="margin-bottom: 5px">
                        شاید بخواهید در خصوص
                        <a href="{{route('policies')}}"> حریم خصوصی و قوانین سایت </a>
                        بیشتر بدانید.
                        در صورت نیاز به کمک، صفحه
                        <a href="#"> راهنما </a>
                        را بخوانید و در صورت نیاز
                        <a href="#"> با ما تماس بگیرید. </a>
                    </div>
                    <div class="aboutShazdeLink">
                        این سایت متعلق به مجموعه کوچیتا می باشد؛
                        <a href="#"> درباره ما </a>
                        بیشتر بدانید.
                        کوچیتا محصولی از
                        <a href="http://bogendesign.co" style="color: #053a3e !important;"> بوگن دیزاین </a>
                        می باشد؛ ما را بیشتر بشناسید.
                    </div>
                </div>
            </div>
            <div style="display: flex; width: 25%">
                <div class="footerLsideBoxes footHideTabletMenu" ></div>
                <div class="footerLsideBoxes" ></div>
            </div>
        </div>

        <div class="clear-both"></div>
    </div>

    <div class="footerPhoneMenuBar hideOnScreen">
        @if(Auth::check())
            <div data-toggle="modal" data-target="#profile" class="profileBtn">

                <?php
                if(auth()->check())
                    $buPic = getUserPic(auth()->user()->id);
                else
                    $buPic = getUserPic();
                ?>
                <div class="profilePicFooter circleBase type2">
                    <img src="{{isset($buPic) ? $buPic : ''}}" style="width: 100%; border-radius: 50%">
                </div>
                <div class="profileBtnText">
                    <span>سلام</span>
                    <span>{{auth()->user()->username}}</span>
                </div>
            </div>
        @else
            <div class="login-button">
                <span class="footerMenuBarLinks">ورود</span>
                <span class="ui_icon member"></span>
            </div>
        @endif
        <div class="openSearchPanPage">
            <span class="footerMenuBarLinks">جست‌و‌جو</span>
            <span class="ui_icon search"></span>
        </div>

        <div onclick="goToUpload()">
            <span class="footerMenuBarLinks">
                بارگزاری محتوا
            </span>
        </div>

        <div data-toggle="modal" data-target="#otherPossibilities">
            <span class="footerMenuBarLinks">منو</span>
            <span class="ui_icon more-horizontal"></span>
        </div>

    </div>

    <div class="modal" id="otherPossibilities">
        <div class="mainPopUp leftPopUp">
            <div style="display: flex; flex-direction: column;">
                @if($hasLive)
                    <a href="{{route('streaming.live', ['room' => $hasLive])}}" class="headerNavTitle" style="color:black;">
                        <img src="{{URL::asset('images/mainPics/anten.png')}}" class="antenIcon1">
                        نمایش زنده
                    </a>
                @endif
                <div class="headerNavTitle " onclick="openCategoryMenu()">دسته بندی ها</div>
                <div class="headerNavTitle">فراخوان</div>
                <div class="headerNavTitle">همکاری با ما</div>
            </div>

            <div class="hideOnScreen phoneFooterStyle">
                <div class="phoneFooterLogo">
                    <img src="{{URL::asset('images/mainPics/vodLobo.png')}}" class="content-icon" width="100%">
                </div>
                <div class="phoneDescription">
                    <div class="phoneDescriptionText">کوچیتا تی وی، سرویس VOD اختصاصی گردگشری در ایران، برای تماشای آنلاین و زنده محتواهای بصری و صوتی در
                        تمامی حوزه‌های گردشگری و سفر می‌باشد. در این سرویس تمام مخاطبان، سفردوستان، دارندگان کسب
                        و کارهای مرتبط با سفر، می‌توانند ویدیوهای خود را آپلود کرده و با مخاطبان خود در ارتباط باشند.
                        تلویزیون کوچیتا با ارائه محتواهای آموزشی و تفریحی در حوزه تخصصی سفر و گردشگری، تنها منبع جامع در ایران می‌باشد.
                        شما با تماشای تلویزیون کوچیتا، می‌توانید به صورت تخصصی و با بالاترین کیفیت در
                        حوزه‌هایی مثل ایرانگردی، جهان‌گردی، سفر ماجراجویانه، رکورد، سفرهای زیارتی،پیاده‌روی، سافاری، رفتینگ، کمپینگ، کوهنوردی، بوم‌‌گردی، اطلاعات جامع را کسب کنید.
                    </div>
                    <div class="phoneDescriptionSelects">

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->check())
        <div class="modal" id="profile">
            <div class="mainPopUp rightPopUp">
                <div id="lp_register">
                    <div>
                        <div class="modules-membercenter-member-profile position-relative">

                            <div class="profileBlock">

                                <div id="" class="targets profileInfosDetails col-xs-8">

                                    <p class="since">
                                        <b>
                                            {{isset($user->first_name) ? $user->first_name : $user->username}}
                                        </b>
                                    </p>
                                    <div class="ageSince">
                                        <div class="since">عضو شده از</div>
                                        <div class="since">
                                            {{--                                        {{$user->created}}--}}
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xs-4">
                                    {{--                                @if(!$user->uploadPhoto)--}}
                                    <img class="avatarUrl"
                                         src="{{isset($buPic) ? $buPic : ''}}"
                                         height="60" width="60"/>
                                    {{--                                @else--}}
                                    {{--                                    <img class="avatarUrl"--}}
                                    {{--                                         src="{{URL::asset('userProfile') . "/" . $user->picture}}"--}}
                                    {{--                                         height="60" width="60"/>--}}
                                    {{--                                @endif--}}
                                </div>
                            </div>

                            <div class="aboutMeDesc">
                                <div class="editInfoBtn" onclick="toggleEditInfoMenu(this)">
                                    ویرایش اطلاعات
                                    <div class="categoryLeftArrow editUserInfoFooterPhoneButton"></div>
                                </div>
                                <div class="editProfileMenu item display-none">
                                    <a name="edit-profile" class="menu-link" href="{{URL('accountInfo')}}">ویرایش اطلاعات کاربری</a>
                                    <a name="edit-photo" class="menu-link" href="{{URL('editPhoto')}}">ویرایش عکس</a>
                                    <a name="subscriptions" class="menu-link" href="">اشتراک ها</a>
                                </div>
                            </div>
                        </div>
                        <div class="profileBtnActionMobile">
                            <a type="button" class="btn btn-warning pp_btns" href="{{route('profile.show', ['user' => auth()->user()->username])}}">صفحه پروفایل</a>
                            <a type="button" class="btn btn-primary pp_btns">صفحه من</a>
                            <a type="button" class="btn btn-danger pp_btns" href="{{route('logout')}}">خروج</a>
                        </div>
                    </div>
                    @if(isset($profilePage) && $profilePage == 1)
                        <div class="profileMenuResponsive">
                            @if($mode == "profile")
                                <div id="Profile" class="profile col-xs-6 profileMenuLinks">
                                    <a id="profileLinkColor1" href="{{URL('profile')}}">صفحه کاربری</a>
                                </div>
                            @else
                                <div id="Profile" class="profile col-xs-6 profileMenuLinks">
                                    <a id="profileLinkColor2" href="{{URL('profile')}}">صفحه کاربری</a>
                                </div>
                            @endif
                            @if($mode == "profileActivities")
                                <div id="ProfileActivities" class="profileActivities col-xs-6 profileMenuLinks">
                                    <a id="profileLinkColor1" href="{{URL('profile')}}">فعالیت‌های من</a>
                                </div>
                            @else
                                <div id="Profile" class="profileActivities col-xs-6 profileMenuLinks">
                                    <a id="profileLinkColor2" href="{{URL('profile')}}">فعالیت‌های من</a>
                                </div>
                            @endif
                            @if($mode == "badge")
                                <div id="BadgeCollection" class="badgeCollection col-xs-6 profileMenuLinks">
                                    <a id="BadgeCollectionLinkColor1" href="{{route('badge')}}">مدال‌های گردشگری</a>
                                </div>
                            @else
                                <div id="BadgeCollection" class="badgeCollection col-xs-6 profileMenuLinks">
                                    <a id="BadgeCollectionLinkColor2" href="{{route('badge')}}">مدال‌های گردشگری</a>
                                </div>
                            @endif

                            <div class="travelMap targets position-relative col-xs-6 profileMenuLinks" id="targetHelp_5">
                                <a href="{{route('soon')}}">سفرنامه من</a>
                                <div id="helpSpan_5" class="helpSpans hidden row">
                                    <span class="introjs-arrow"></span>
                                    <div class="col-xs-12">
                                        <p>با استفاده از این منو می‌توانید به سایر بخش‌های پروفایل کاربری خود بروید.</p>
                                    </div>
                                    <div class="col-xs-12">
                                        <button data-val="5" class="btn btn-success nextBtnsHelp" id="nextBtnHelp_5">بعدی</button>
                                        <button data-val="5" class="btn btn-primary backBtnsHelp" id="backBtnHelp_5">قبلی</button>
                                        <button class="btn btn-danger exitBtnHelp">خروج</button>
                                    </div>
                                </div>
                            </div>

                            {{--                <li id="Saves" class="saves"></li>--}}
                            @if($mode == "message")
                                <div id="Messages" class="messages col-xs-6 profileMenuLinks">
                                    <a id="messageLinkColor1" href="{{URL('messages')}}">پیام‌ها</a>
                                </div>
                            @else
                                <div id="Messages" class="messages col-xs-6 profileMenuLinks">
                                    <a id="messageLinkColor2" href="{{URL('messages')}}">پیام‌ها</a>
                                </div>
                            @endif
                            {{--                <div id="Bookings" class="bookings col-xs-6 profileMenuLinks">--}}
                            {{--                    <a id="bookingLinkColor2" href="{{route('soon')}}">رزروها</a>--}}
                            {{--                </div>--}}
                            {{--                <div id="PaymentOptions" class="paymentOptions col-xs-6 profileMenuLinks">--}}
                            {{--                    <a id="paymentOptionsLinkColor2" href="{{route('soon')}}">پروازها</a>--}}
                            {{--                </div>--}}
                            {{--                            @if($mode == "setting")--}}
                            {{--                                <div id="Settings" class="settingColor1 settings col-xs-6 profileMenuLinks">--}}
                            {{--                            @else--}}
                            {{--                                <div id="Settings" class="settingColor2 settings col-xs-6 profileMenuLinks">--}}
                            {{--                            @endif--}}
                            {{--                                    تنظیمات--}}
                            {{--                                    <div class="settingsArrow"></div>--}}
                            {{--                                    <div class="settingsDropDown" id="settingDropDownMainDiv">--}}
                            {{--                                        <a href="{{URL('accountInfo')}}">اطلاعات کاربر</a>--}}
                            {{--                                        <?php--}}
                            {{--                                        $level = Auth::user()->level;--}}
                            {{--                                        ?>--}}

                            {{--                                        @if($level == 1 || $level == 3)--}}
                            {{--                                            <a title="Control Content" href="{{route('getReports')}}">مدیریت گزارشات</a>--}}
                            {{--                                        @endif--}}

                            {{--                                        @if(Auth::user()->level == 1)--}}
                            {{--                                            --}}{{--<a title="ages" href="{{route('specialAdvice')}}">پیشنهاد های ویژه</a>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                        </div>
                    @else
                        <div class="profileScoreMainDiv">
                            <div class="modules-membercenter-progress-header " data-backbone-name="modules.membercenter.ProgressHeader" data-backbone-context="Social_CompositeMember, Member">
                                <div class="title" id="myHonorsText">
                                    <h3>امتیازات من</h3>
                                </div>

                                <a class="link" {{--onclick="initHelp(16, [], 'MAIN', 100, 400)"--}}>
                                    <div></div>
                                </a>
                            </div>

                            <div class="memberPointInfo">
                                <div class="modules-membercenter-total-points">
                                    <div data-direction="left" class="targets">
                                        <div class="points_info tripcollectiveinfo" onclick="showElement('activityDiv')">
                                            <div class="label"> امتیاز کل شما </div>
                                        </div>
                                    </div>
                                    <div class="mainDivTotalPoint">
                                        <div class="points">{{$userTotalPointFooter}}</div>
                                        <a href="">سیستم امتیازدهی</a>
                                    </div>
                                    <div class="points_to_go">
                                    <span>
                                        <b class="points"> {{$nextLevelFooter}} </b>
                                        <span>امتیاز  مانده به مرحله بعد</span>
                                    </span>
                                    </div>
                                </div>
                                <div class="modules-membercenter-level-progress">
                                    <div data-direction="left" id="targetHelp_9" class="targets progress_info tripcollectiveinfo">
                                        <div onclick="showElement('levelDiv')">
                                            <div class="labels">
                                                <div class="right label">مرحله فعلی</div>
                                                <div class="float-leftImp label">مرحله بعدی</div>
                                            </div>
                                            <div class="progress_indicator">

                                                <div class="next_badge myBadge">
                                                    name
                                                </div>
                                                <div class="meter">
                                                    <span id="progressIdPhone" class="progress"></span>
                                                </div>
                                                <div class="current_badge myBadge">
                                                    name
                                                </div>
                                            </div>
                                            <div class="text-align-center">
                                                <a class="cursor-pointer color-black">مشاهده سیستم سطح بندی</a>
                                            </div>
                                            <div class="clear fix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="userProfileActivitiesMainDiv rightColBoxes">
                        <div class="mainDivHeaderText">
                            <h3>شرح فعالیت‌ها</h3>
                        </div>
                        <div class="activitiesMainDiv">
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">گذاشتن پست</div>
                                <div class="activityNumbers">پست 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">آپلود عکس</div>
                                <div class="activityNumbers">عکس 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">آپلود فیلم</div>
                                <div class="activityNumbers">فیلم 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">آپلود فیلم 360</div>
                                <div class="activityNumbers">فیلم 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">پرسیدن سؤال</div>
                                <div class="activityNumbers">سؤال 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">پاسخ به سؤال دیگران</div>
                                <div class="activityNumbers">پاسخ 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">امتیازدهی</div>
                                <div class="activityNumbers">مکان 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">پاسخ به سؤالات اختیاری</div>
                                <div class="activityNumbers">پاسخ 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">ویرایش مکان</div>
                                <div class="activityNumbers">مکان 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">پیشنهاد مکان جدید</div>
                                <div class="activityNumbers">مکان 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">نوشتن مقاله</div>
                                <div class="activityNumbers">مقاله 0</div>
                            </div>
                            <div class="activitiesLinesDiv">
                                <div class="activityTitle">معرفی دوستان</div>
                                <div class="activityNumbers">معرفی 7</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>

        // phone functions
        function lp_selectMenu(id , element) {
            $('.lp_eachMenu').removeClass('lp_selectedMenu');
            $(element).addClass('lp_selectedMenu');
            $('.lp_others_content').addClass('hidden');
            $('#' + id).removeClass('hidden');
        }

        function toggleEditInfoMenu(elm) {
            $('.editUserInfoFooterPhoneButton').toggleClass('topArrow');
            $(elm).next().toggleClass('display-none');
            $(elm).next().toggleClass('display-flex');
        }

        // phone Article func
        function lp_selectArticleFilter(id , element) {
            $('.lp_ar_eachFilters').removeClass('lp_ar_selectedMenu');
            $(element).addClass('lp_ar_selectedMenu');
            $('.lp_ar_contentOfFilters').addClass('hidden');
            $('#' + id).removeClass('hidden');
        }

        function showMorefooter() {
            $('.footMoreLessBtnText').toggleClass('hidden');
            $('#aboutShazde').toggleClass('aboutShazdeMoreLess');
        }
    </script>

    @if(Auth::check())
        <script>
            function initialProgressFooter() {
                var b = 10;
                $("#progressIdPhone").css("width", b + "%");
            }
            initialProgressFooter();
        </script>
    @endif

</footer>
