<html>

<head>
    <script src="{{URL::asset('js/ajax.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>

    <link href="https://vjs.zencdn.net/7.7.5/video-js.css" rel="stylesheet" />

    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src="https://vjs.zencdn.net/7.7.5/video.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.15.0/videojs-contrib-hls.min.js"></script>

    <style>

        .hidden {
            display: none;
        }

    </style>

</head>

<body>

<div id="videoDiv">
    <video id="video" class="video-js playads videos" width="300px" height="300px" controls autoplay></video>
</div>

<script>

    $(document).ready(function () {

        var myPlayer = videojs('video', {autoplay: 'any'});
        var url = '{{$url}}';

        myPlayer.src({
            src: 'https://yom.ir/vod/144p.m3u8',
            type: 'application/x-mpegURL',
            withCredentials: false
        });
    });

</script>
</body>

</html>

