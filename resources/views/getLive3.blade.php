<html>

<head>
    <script src="{{URL::asset('js/ajax.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>

    <style>

        .hidden {
            display: none;
        }

    </style>

</head>

<body>

<div id="videoDiv" style="width: 100%; heigth: 100%;">
</div>

<div id="splash" style="width: 100%; height: 100%; position: absolute; top:0; left: 0; z-index = 2">
<center>
<h3>preparing live stream</h3>
<button style="background-color: #ccc; cursor: pointer; border: 1px solid black;" onclick="$('#splash').addClass('hidden')">click here to start</button>
</center>
</div>

<script>
// 	<video id="video" class="videos" width="100%" height="100%" autoplay></video>
//	<video id="video2" class="hidden videos" width="100%" height="100%" preload="auto" playsinline autoplay></video>

    var curr = 0;
    var lastSeen = 0;
    var lastPlay = -1;

    var server = "185.239.106.228:8080";
    var server2 = "185.239.106.228/";
    // var server = "192.168.1.7:8080";
    // var server2 = "192.168.1.7/streamServer/";

    var pool_size = 10;
    var queue = [];
    var socket = null;
	var timer;
	var d = new Date();

    function WebSocketTest() {

        if ("WebSocket" in window) {

            // alert("WebSocket is supported by your Browser!");

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

            socket.onopen = (msg) => {
		socket.send(0x01);
		timer = setInterval(function() {
			socket.send(0x3);
		}, 5000);
            };

            socket.onmessage = (msg) => {
		//queue.push(msg.data);
		console.log(msg.data);
                play(msg.data);
		//play();
            };

            socket.onclose = (msg) => {
                alert('disconnected');
		clearInterval(timer);
            };

        } else {
            alert("WebSocket NOT supported by your Browser!");
        }
    }

    $(document).ready(function () {
	WebSocketTest();


        var newElem = "";

        for(i = 0; i < pool_size; i++) {
            newElem += '<video id="video' + i + '" class="hidden videos" width="100%" height="100%" muted="muted"></video>';
        }

        $("#videoDiv").append(newElem);


    });

    function play(url) {

        $('#video' + (curr % pool_size)).attr('src', 'https://' + server2 + 'rawFiles/' + url + '.webm');

        if(curr >= 3) {
	    currTime = d.getTime();
	    diff = 4000 - currTime + lastPlay;
	    if (diff <= 0) {
	    	$("#video" + (lastSeen % pool_size)).removeAttr('muted').get(0).play();
            	$('.videos').addClass('hidden').attr('muted', 'muted');
		$("#video" + (lastSeen % pool_size)).removeClass('hidden');
		lastPlay = currTime;
	    	lastSeen++;
	    }
	    else {
		setTimeout(function() {
            		$('.videos').addClass('hidden').attr('muted', 'muted');
	    		$("#video" + (lastSeen % pool_size)).removeClass('hidden').removeAttr('muted').get(0).play();
			lastPlay = currTime;
	    		lastSeen++;
		}, diff);
	    }
        }

        curr++;
    }


    function play2(data) {

        var reader = new FileReader();

        reader.onloadend = function() {

            var byteCharacters = atob(reader.result.slice(reader.result.indexOf(',') + 1));

            var byteNumbers = new Array(byteCharacters.length);

            for (var i = 0; i < byteCharacters.length; i++) {

                byteNumbers[i] = byteCharacters.charCodeAt(i);

            }

            var byteArray = new Uint8Array(byteNumbers);
            var blob = new Blob([byteArray], {type: 'video/webm'});
		console.log(blob);
            var url = URL.createObjectURL(blob);
		console.log(url);

	    queue.push(url);
            if(curr >= pool_size - 2) {
		$('#video').attr('src', queue.shift());
	    }

	    curr++;
	};

        reader.readAsDataURL(data);
        return;
	}

</script>

</body>

</html>
