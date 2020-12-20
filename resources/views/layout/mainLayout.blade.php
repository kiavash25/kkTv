<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:type" content="website" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta name="theme-color" content="#4dc7bc"/>
    <meta name="msapplication-TileColor" content="#4dc7bc">
    <meta name="msapplication-TileImage" content="{{ asset('images/blank.jpg') }}">
    <meta name="twitter:card" content="summary"/>
    <meta property="og:url" content="{{Request::url()}}" />
    <meta property="og:site_name" content="کوچیتا تی وی | اولین تلویزیون اینترنتی گردشگری و صنایع دستی ایران" />


{{--    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158914626-1"></script>--}}
{{--    <script>--}}
{{--        window.dataLayer = window.dataLayer || [];--}}
{{--        function gtag(){dataLayer.push(arguments);}--}}
{{--        gtag('js', new Date());--}}

{{--        gtag('config', 'UA-158914626-1');--}}
{{--    </script>--}}

    <link rel="icon" href="{{URL::asset('images/icons/TVFAV0.svg')}}" sizes="any" type="image/svg+xml">
    <link rel="apple-touch-icon-precomposed" href="{{URL::asset('images/icons/TVFAV0.svg')}}" sizes="any" type="image/svg+xml">

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
    // sendSeenPageLog();

    $('.closeThisMyModal').on('click', e => {
        let parent = $(e.target).parent();
        while(!parent.hasClass('myModal'))
            parent = parent.parent();
        parent.removeClass('show')
    });

    openMyModal = _id => $(`#${_id}`).addClass('show');
    closeMyModal = _id => $(`#${_id}`).removeClass('show');

    window.mobileCheck = function() {
        let check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    };

</script>

@yield('script')

</html>

