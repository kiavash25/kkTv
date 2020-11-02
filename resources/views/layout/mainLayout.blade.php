<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta name="theme-color" content="#4dc7bc"/>
    <meta name="msapplication-TileColor" content="#4dc7bc">
    <meta name="msapplication-TileImage" content="{{ asset('images/blank.jpg') }}">
    <meta name="twitter:card" content="summary"/>
    <meta property="og:url" content="{{Request::url()}}" />
    <meta property="og:site_name" content="سامانه جامع گردشگری کوچیتا" />

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

{{--    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158914626-1"></script>--}}
{{--    <script>--}}
{{--        window.dataLayer = window.dataLayer || [];--}}
{{--        function gtag(){dataLayer.push(arguments);}--}}
{{--        gtag('js', new Date());--}}

{{--        gtag('config', 'UA-158914626-1');--}}
{{--    </script>--}}

    <link rel="icon" href="{{URL::asset('images/icons/mainIcon.svg')}}" sizes="any" type="image/svg+xml">
    <link rel="apple-touch-icon-precomposed" href="{{URL::asset('images/icons/mainIcon.svg')}}" sizes="any" type="image/svg+xml">

    <link rel='stylesheet' type='text/css' href='{{URL::asset('css/default/bootstrap.min.css')}}' />
    <link rel="stylesheet" href="{{URL::asset('css/default/swiper.css')}}">
    <link rel='stylesheet' type='text/css' href='{{ asset('css/layout/common.css?v='.$fileVersion) }}' />
    <link rel="stylesheet" href="{{ asset('css/default/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout/icons.css?v='.$fileVersion) }}">
    <link rel="stylesheet" href="{{ asset('css/app.css?v='.$fileVersion) }}">


    <script src="{{URL::asset('js/default/jquery-3.4.1.min.js')}}"></script>
    <script src="{{URL::asset('js/default/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('js/default/angular.js')}}"></script>
    <script src="{{URL::asset('js/default/swiper.min.js')}}"></script>
    <script src="{{URL::asset('js/default/jquery-ui.js')}}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
    </script>

    @yield('head')

    <script>
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
            for(i = 0; i < imgs.length; i++)
                resizeThisImg($(imgs[i]))
        }

        function resizeThisImg(_element){
            var img = $(_element);
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

        function resizeRows(_class){
            let content = $($('.' + _class)[0]);
            let parent = content.parent();
            let margin = parseInt(content.css('margin-left').replace('px', ''));
            let width = parseInt(content.css('width').replace('px', '')) + (2 * margin);
            let sourceWidth = parseInt(parent.parent().css('width').replace('px', ''));

            let newWidth = sourceWidth - (sourceWidth % width);
            parent.css('width', newWidth);
        }

    </script>

</head>

<body class="rebrand_2017 desktop HomeRebranded  js_logging" style="background-color: #EAFBFF;">

    <div id="darkModeMainPage" class="ui_backdrop dark" ></div>

    @include('component.loading')

    @include('component.alerts')

    @include('component.videoSuggestionPack')

    @include('component.searchPan')

    @include('component.categoryTable')

    @include('component.playListObj')

    @if(!Auth::check())
        @include('component.nloginPopup')
    @endif

    @include('layout.header')

    <div class="streamBody" style="padding-top: 10px">
        @yield('body')
    </div>

    @include('layout.footer')

</body>


<script src="https://vjs.zencdn.net/5.19.2/video.js"></script>
<script src="{{URL::asset('js/video/hls.min.js?v=v0.9.1')}}"></script>
<script src="{{URL::asset('js/video/videojs5-hlsjs-source-handler.min.js?v=0.3.1')}}"></script>
<script src="{{URL::asset('js/video/vjs-quality-picker.js?v=v0.0.2')}}"></script>
<script src="{{URL::asset('js/default/load-image.all.min.js')}}"></script>

<script>
    var hasLogin = {{auth()->check() ? 1 : 0}};
    window.csrfTokenGlobal = '{{csrf_token()}}';

    function cleanImgMetaData(_input, _callBack){
        options = { canvas: true };
        loadImage.parseMetaData(_input.files[0], function(data) {
            if (data.exif)
                options.orientation = data.exif.get('Orientation');

            loadImage(
                _input.files[0],
                function(canvas) {
                    var imgDataURL = canvas.toDataURL();
                    if(typeof _callBack === 'function') {
                        blob = dataURItoBlob(imgDataURL);
                        _callBack(imgDataURL, blob);
                    }
                },
                options
            );
        });
    }

    function dataURItoBlob(dataURI) {
        var byteString = atob(dataURI.split(',')[1]);
        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]
        var ab = new ArrayBuffer(byteString.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++)
            ia[i] = byteString.charCodeAt(i);

        var blob = new Blob([ab], {type: mimeString});
        return blob;
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    $(document).ready(function(){
        resizeFitImg('resizeImgClass');
    });
    $(window).resize(function(){
        resizeFitImg('resizeImgClass');
    });

    $('.openSearchPanPage').on('click', openMainSearch);

    function hideElement(_element){
        $(".dark").hide();
        $("#" + _element).addClass('hidden');
    }

    window.seenPageLogId = 0;
    window.isMobile =  0;

    function sendSeenPageLog(){
        $.ajax({
            type: 'post',
            url: '{{route('log.storeSeen')}}',
            data: {
                _token: '{{csrf_token()}}',
                seenPageLogId: window.seenPageLogId,
                isMobile: window.isMobile,
                width: $(window).width(),
                height: $(window).height(),
                url: document.location.pathname
            },
            success: response => {
                if(response.status == 'ok')
                    window.seenPageLogId = response.seenPageLogId;
                setTimeout(sendSeenPageLog, 5000);
            },
            error: err => setTimeout(sendSeenPageLog, 5000)
        })
    }
    sendSeenPageLog();

    $(window).resize(function(){
        resizeRows('videoInList')
    });

    $('.closeThisMyModal').on('click', e => {
        let parent = $(e.target).parent();
        while(!parent.hasClass('myModal'))
            parent = parent.parent();
        parent.removeClass('show')
    });

    openMyModal = _id => $(`#${_id}`).addClass('show');
    closeMyModal = _id => $(`#${_id}`).removeClass('show');

</script>

@yield('script')

</html>

