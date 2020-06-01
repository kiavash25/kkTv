<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta name="theme-color" content="#4dc7bc"/>
    <meta name="msapplication-TileColor" content="#4dc7bc">
    <meta name="msapplication-TileImage" content="{{ asset('images/icons/mainIcon.png') }}">
    <meta name="twitter:card" content="summary"/>
    <meta property="og:url" content="{{Request::url()}}" />
    <meta property="og:site_name" content="سامانه جامع گردشگری کوچیتا" />

    <title> کوچیتا تی وی </title>
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:type" content="website" />
    <meta name="title" content="کوچیتا | سامانه جامع گردشگری ایران و شبکه اجتماعی گردشگران" />
    <meta name='description' content='کوچیتا، سامانه جامع گردشگری ایران و شبکه اجتماعی گردشگران. اطلاعات اماکن و جاذبه ها، هتل ها، بوم گردی، ماجراجویی، آموزش سفر، فروشگاه صنایع‌دستی ، پادکست سفر' />
    <meta name='keywords' content='کوچیتا، هتل، تور ، سفر ارزان، سفر در ایران، بلیط، تریپ، نقد و بررسی، سفرنامه، کمپینگ، ایران گردی، آموزش سفر، مجله گردشگری، مسافرت، مسافرت داخلی, ارزانترین قیمت هتل ، مقایسه قیمت ، بهترین رستوران‌ها ، بلیط ارزان ، تقویم تعطیلات' />
    <meta property="og:image" content="{{URL::asset('_images/nopic/blank.jpg')}}"/>
    <meta property="og:image:secure_url" content="{{URL::asset('_images/nopic/blank.jpg')}}"/>
    <meta property="og:image:width" content="550"/>
    <meta property="og:image:height" content="367"/>
    <meta name="twitter:image" content="{{URL::asset('_images/nopic/blank.jpg')}}"/>


{{--    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158914626-1"></script>--}}
{{--    <script>--}}
{{--        window.dataLayer = window.dataLayer || [];--}}
{{--        function gtag(){dataLayer.push(arguments);}--}}
{{--        gtag('js', new Date());--}}

{{--        gtag('config', 'UA-158914626-1');--}}
{{--    </script>--}}

    <link rel='stylesheet' type='text/css' href='{{URL::asset('css/default/bootstrap.min.css')}}' />
    <link rel="icon" href="{{URL::asset('images/icons/mainIcon.svg')}}" sizes="any" type="image/svg+xml">
    <link rel="apple-touch-icon-precomposed" href="{{URL::asset('images/icons/mainIcon.svg')}}" sizes="any" type="image/svg+xml">

    <link rel="stylesheet" href="{{URL::asset('css/default/swiper.css')}}">

    <script src="{{URL::asset('js/default/jquery-3.4.1.min.js')}}"></script>
    <script src="{{URL::asset('js/default/angular.js')}}"></script>
    <script src="{{URL::asset('js/default/swiper.min.js')}}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
    </script>

{{--    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/theme2/long_lived_global_legacy_2.css')}}"/>--}}
    <link rel='stylesheet' type='text/css' href='{{ asset('css/layout/common.css') }}' />
    <link rel="stylesheet" href="{{ asset('css/default/font-awesome.min.css') }}">

    @yield('head')

</head>

<body class="rebrand_2017 desktop HomeRebranded  js_logging" style="background-color: #EAFBFF;">

    <div id="darkModeMainPage" class="ui_backdrop dark" ></div>
    @include('component.loading')
    @include('component.alerts')
    @include('component.videoSuggestionPack')
    @include('component.searchPan')
    @include('component.categoryTable')

{{--    @if(!Auth::check())--}}
{{--        @include('component.loginPopup')--}}
{{--    @endif--}}

    @include('layout.header')

    <div class="streamBody" style="padding-top: 10px">
        @yield('body')
    </div>

    @include('layout.footer')

</body>

<script>
    var hasLogin = {{auth()->check() ? 1 : 0}};

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function checkLogin(){
        if (!hasLogin) {
            showLoginPrompt('{{Request::url()}}');
            return false;
        }
        else
            return true;
    }

    function resizeFitImg(_class) {
        var imgs = $('.' + _class);
        for(i = 0; i < imgs.length; i++){
            var img = $(imgs[i]);
            var imgW = img.width();
            var imgH = img.height();

            var secW = img.parent().width();
            var secH = img.parent().height();

            if(imgH < secH){
                img.css('height', '100%');
                img.css('width', 'auto');
            }
            else if(imgW < secW){
                img.css('width', '100%');
                img.css('height', 'auto');
            }
        }
    }

    function resizeRows(_class){
        let content = $($('.' + _class)[0]);
        let parent = content.parent();
        let margin = parseInt(content.css('margin-left').replace('px', ''));
        let width = parseInt(content.css('width').replace('px', '')) + (2 * margin);
        let sourceWidth = parseInt(parent.parent().css('width').replace('px', ''));

        let newWidth = sourceWidth - (sourceWidth % width);
        parent.css('width', newWidth);

    }

    $(document).ready(function(){
        resizeFitImg('resizeImgClass');
    });
    $(window).resize(function(){
        resizeFitImg('resizeImgClass');
    });

    $('.openSearchPanPage').on('click', openMainSearch);

</script>

@yield('script')

</html>

