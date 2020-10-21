<link rel="stylesheet" href="{{URL::asset('css/layout/vodHeader.css')}}">

<?php
    if(auth()->check())
        $user = auth()->user();
?>

<style>

</style>

<nav>
    <div class="headerLogo ">
        <a href="{{route('index')}}" class="global-nav-logo" style="display: flex; align-items: center; height: 100%; padding: 7px 0px;">
            <img src="{{ asset('images/mainPics/vodLobo.png')}}" alt="کوچیتا" style="width: auto; height: 100%;"/>
        </a>
    </div>

    <div class="headerTab hideOnPhone">
        @if(true)
            <a href="https://koochitatv.com/getLive/2" class="headerNavTitle">
                <img src="{{URL::asset('images/mainPics/anten.gif')}}" class="antenIcon1">
                پخش زنده
            </a>
        else
            <div class="headerNavTitle">
                <img src="{{URL::asset('images/mainPics/anten.png')}}" class="antenIcon1">
                پخش زنده
            </div>
        @endif
{{--        headerNavTitleActive--}}
        <div class="headerNavTitle " onclick="openCategoryMenu()">دسته بندی ها</div>
        <div class="headerNavTitle">فراخوان</div>
        <div class="headerNavTitle">همکاری با ما</div>
        <div class="headerNavTitle openSearchPanPage">جستجو</div>
    </div>

    <div class="headerLeftSection hideOnPhone">
        <div class="loginButton" onclick="goToUpload()" style="padding-left: 5px; margin-left: 10px">
            <div>بارگذاری محتوا</div>
            <div class="addIcon">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </div>
        </div>
        @if(auth()->check())
            <div class="loginButton bookMarkHeaderIcon"></div>
            <div class="loginButton userNameHeader" style="padding-left: 10px">
                <div style="z-index: 9">
                    {{auth()->user()->username}}
                </div>
                <div class="userHeaderIcon"></div>
                <div class="userHeaderMenu">
                    <a href="https://koochita.com/profile/index/{{auth()->user()->username}}" class="userHeaderMenuTab">
                        صفحه ی کاربری
                    </a>
                    <a href="{{route('logout')}}" class="userHeaderMenuTab">
                        خروج
                    </a>
                </div>
            </div>
        @else
            <a class="login-button loginButton" title="Join">ورود / ثبت نام</a>
        @endif
    </div>

    <div class="headerRightSection hideOnPhone" onclick="showHideMenu()">
        <div class="headerLine headerLine1"></div>
        <div class="headerLine headerLine2"></div>
        <div class="headerLine headerLine3"></div>
    </div>

    <div class="headerRightTabs hideOnPhone">
        @if($hasLive !== false)
            <a href="{{route('streaming.live', ['room' => $hasLive] )}}" class="headerNavTitle">
                <img src="{{URL::asset('images/mainPics/anten.gif')}}" class="antenIcon1">
                پخش زنده
            </a>
        @else
            <div class="headerNavTitle">
                <img src="{{URL::asset('images/mainPics/anten.png')}}" class="antenIcon1">
                پخش زنده
            </div>
        @endif

        <div class="headerNavTitle" onclick="openCategoryMenu()">دسته بندی ها</div>
        <div class="headerNavTitle">فراخوان</div>
        <div class="headerNavTitle">همکاری با ما</div>
        <div class="headerNavTitle openSearchPanPage">جستجو</div>
    </div>

</nav>


<script>
{{--    var getBookMarksPath = '{{route('getBookMarks')}}';--}}

    function showHideMenu(){
        $('.headerLine').toggleClass('change');
        $('.headerRightSection').toggleClass('change');
        $('.headerRightTabs').toggleClass('change');
    }



    function hideAllTopNavs(){
        $("#alert").hide();
        $("#my-trips-not").hide();
        $("#profile-drop").hide();
        $("#bookmarkmenu").hide();
    }

    hideAllTopNavs();

    $(document).ready(function(){

        $(".menu-bars").click(function(){
            $("#menu_res").removeClass('off-canvas');
        });

        $("#close_menu_res").click(function(){

            $("#menu_res").addClass('off-canvas');
        });
    });

    function headerActionsToggle() {

        $('.collapseBtnActions').animate({transform: 'rotate(90deg)'})


        if($('.global-nav-actions').hasClass('display-flexImp')) {

            $('.global-nav-actions').animate({width: "0"},
                function () {
                    $('.global-nav-actions').toggleClass('display-flexImp');
                });
        }
        else {
            $('.global-nav-actions').animate({width: "270px"});
            $('.global-nav-actions').toggleClass('display-flexImp');
        }
    }

    function goToUpload() {
        if (!hasLogin) {
            showLoginPrompt();
            return;
        }
        location.href = "{{route('video.uploadPage')}}";
    }

</script>

@if(Auth::check())
    <script>
        var locked = false;
        var superAccess = false;

        $('#nameTop').click(function(e) {

            if( $("#profile-drop").is(":hidden")) {
                hideAllTopNavs();
                $("#profile-drop").show();
            }
            else
                hideAllTopNavs();
        });

        $('#memberTop').click(function(e) {
            if( $("#profile-drop").is(":hidden")) {
                hideAllTopNavs();
                $("#profile-drop").show();
            }
            else
                hideAllTopNavs();
        });

        $('#bookmarkicon').click(function(e) {
            if( $("#bookmarkmenu").is(":hidden")){
                hideAllTopNavs();
                $("#bookmarkmenu").show();
                showBookMarks('bookMarksDiv');
            }
            else
                hideAllTopNavs();
        });

        $('.notification-bell').click(function(e) {
            if( $("#alert").is(":hidden")) {
                hideAllTopNavs();
                $("#alert").show();
            }
            else
                hideAllTopNavs();
        });

        $("#Settings").on({
            mouseenter: function () {
                $(".settingsDropDown").show()
            }, mouseleave: function () {
                $(".settingsDropDown").hide()
            }
        });

        function showBookMarks(containerId) {

            $("#" + containerId).empty();

            $.ajax({
                type: 'post',
                url: getBookMarksPath,
                success: function (response) {

                    response = JSON.parse(response);

                    for(i = 0; i < response.length; i++) {
                        element = "<div>";
                        element += "<a class='masthead-recent-card' target='_self' href='" + response[i].placeRedirect + "'>";
                        element += "<div class='media-left'>";
                        element += "<div class='thumbnail' style='background-image: url(" + response[i].placePic + ");'></div>";
                        element += "</div>";
                        element += "<div class='content-right'>";
                        element += "<div class='poi-title'>" + response[i].placeName + "</div>";
                        element += "<div class='rating'>";
                        element += "<div class='ui_bubble_rating bubble_45'></div><br/>" + response[i].placeReviews + " مشاهده ";
                        element += "</div>";
                        element += "<div class='geo'>" + response[i].placeCity + "</div>";
                        element += "</div>";
                        element += "</a></div>";

                        $("#" + containerId).append(element);
                    }

                }
            });
        }

        // function getRecentlyViews(containerId) {
        //     $("#" + containerId).empty();
        //
        //     $.ajax({
        //         type: 'post',
        //         url: getRecentlyPath,
        //         success: function (response) {
        //
        //             response = JSON.parse(response);
        //
        //             for(i = 0; i < response.length; i++) {
        //                 element = "<div>";
        //                 element += "<a class='masthead-recent-card' style='text-align: right !important;' target='_self' href='" + response[i].placeRedirect + "'>";
        //                 element += "<div class='media-left' style='padding: 0 12px !important; margin: 0 !important;'>";
        //                 element += "<div class='thumbnail' style='background-image: url(" + response[i].placePic + ");'></div>";
        //                 element += "</div>";
        //                 element += "<div class='content-right'>";
        //                 element += "<div class='poi-title'>" + response[i].placeName + "</div>";
        //                 element += "<div class='rating'>";
        //
        //                 if (response[i].placeRate == 5)
        //                     element += "<div class='ui_bubble_rating bubble_50'></div>";
        //                 else if (response[i].placeRate == 4)
        //                     element += "<div class='ui_bubble_rating bubble_40'></div>";
        //                 else if (response[i].placeRate == 3)
        //                     element += "<div class='ui_bubble_rating bubble_30'></div>";
        //                 else if (response[i].placeRate == 2)
        //                     element += "<div class='ui_bubble_rating bubble_20'></div>";
        //                 else
        //                     element += "<div class='ui_bubble_rating bubble_10'></div>";
        //
        //                 element += "<br/>" + response[i].placeReviews + " نقد ";
        //                 element += "</div>";
        //                 element += "<div class='geo'>" + response[i].placeCity + "/ " + response[i].placeState + "</div>";
        //                 element += "</div>";
        //                 element += "</a></div>";
        //
        //                 $("#" + containerId).append(element);
        //             }
        //
        //         }
        //     });
        // }

        // function showRecentlyViews(element) {
        //     if( $("#my-trips-not").is(":hidden")){
        //         hideAllTopNavs();
        //         $("#my-trips-not").show();
        //         getRecentlyViews(element);
        //     }
        //     else
        //         hideAllTopNavs();
        // }


    </script>
@endif
