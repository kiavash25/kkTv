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
        <button style="background-color: #ccc; cursor: pointer; border: 1px solid black;" onclick="$('#splash').addClass('hidden'); WebSocketTest()">click here to start</button>
    </center>
</div>

<script>

//    <video id="video" class="videos" width="100%" height="100%"></video>
//    <video id="video2" class="hidden videos" width="100%" height="100%" preload="auto"></video>

    var curr = 0;
    var lastSeen = 0;
    var lastPlay = -1;

    var server = "185.239.106.228:8080";
    var server2 = "185.239.106.228/";
    //var server = "192.168.100.5:8080";
    //var server2 = "192.168.100.5/streamServer/";

    var pool_size = 10;
    var queue = [];
    var socket = null;
    var timer, timer2;
    var curr_player = null;

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

            socket.onopen = (msg) => {
                socket.send(0x01);
                timer = setInterval(function() {
                    socket.send(0x3);
                }, 5000);

                timer2 = setInterval(function () {

		    if(queue.length < 3 || (curr_player != null && !curr_player.paused)) {
			return;
		    }
			$(".videos").addClass("hidden");
			$("#video" + (lastSeen % pool_size)).removeClass("hidden");
			curr_player = $("#video" + (lastSeen % pool_size)).get(0);
        	        curr_player.play();
                	lastSeen++;
                        queue.shift();

                }, 100);


            };

            socket.onmessage = (msg) => {
                //queue.push(msg.data);
                //console.log(msg.data);
                play3(msg.data);
                //play();
            };

            socket.onclose = (msg) => {
                alert('disconnected');
                clearInterval(timer);
                clearInterval(timer2);
            };

        } else {
            alert("WebSocket NOT supported by your Browser!");
        }
    }

    $(document).ready(function () {

        //WebSocketTest();

        var newElem = "";

        for(i = 0; i < pool_size; i++) {
            newElem += '<video id="video' + i + '" class="hidden videos" width="100%" height="100%"></video>';
        }

        $("#videoDiv").append(newElem);

	//play3("7");

    });

    function play(url) {

        if(curr >= 1) {
            queue.push("1");
        }

        $('#video' + (curr % pool_size)).attr('src', 'https://' + server2 + 'rawFiles/' + url + '.webm');
        curr++;
    }

	function play3(data) {

		var xhr = new XMLHttpRequest();
		xhr.open("GET", 'https://' + server2 + 'rawFiles/' + data + '.webm', true);
		xhr.responseType = "blob";
		xhr.addEventListener("load", function () {
	    		if (xhr.status === 200) {
        			newvideo = xhr.response;
        			var docURL = URL.createObjectURL(newvideo);
				//var vid = document.getElementById("video" + (curr % pool_size));
				//vid.src = docURL;
				$("#video" + (curr % pool_size)).attr('src', docURL);
				queue.push(1);
				curr++;
    			}
		});
		xhr.send();
	}

    function play2(data) {

	$.ajax({
	url: 'https://' + server2 + 'rawFiles/' + data + '.webm',
	type: 'get',
	success: function(data) {
        //var reader = new FileReader();
		console.log(data);

        //reader.onloadend = function() {

            //var byteCharacters = data;
            var byteCharacters = atob(data.slice(data.indexOf(',') + 1));


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
            if(curr >= pool_size - 2 || 1 == 1) {
                $('#video').attr('src', queue.shift());
            }

            curr++;
        //};
        //reader.readAsDataURL(data);
	}
	});
    }

</script>

</body>

</html>

