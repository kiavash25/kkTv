let storeVideoAjax;

$('#videoTags').dropdown({
    apiSettings: {
        url: getTagsURL,
        method: 'POST',
        cache: false,
        beforeXHR: (xhr) => {
            xhr.setRequestHeader('Content-Type', 'application/json');
        },
        beforeSend: (settings) => {
            settings.data = JSON.stringify({
                tag: settings.urlData.query,
            });
            return settings
        },
        onResponse: (response) => {
            let result = [];
            if(response.same == 0) {
                result = [{
                    "name": response.send,
                    "value": 'new_' + response.send,
                    "text": response.send
                }]
            }
            else{
                result = [{
                    "name": response.same.name,
                    "value": 'new_' + response.same.id,
                    "text": response.same.name
                }]
            }
            if(response.tags.length != 0){
                for(let i = 0; i < response.tags.length; i++){
                    result.push({
                        "name" : response.tags[i].name,
                        "value" : 'old_' + response.tags[i].id,
                        "text" : response.tags[i].name
                    })
                }
            }
            response = {
                "success": true,
                "results": result
            };
            // Modify your JSON response into the format SUI wants
            return response
        }
    }
});

$('#videoPlaceRel').dropdown({
    apiSettings: {
        url: totalSearchURL,
        method: 'POST',
        cache: false,
        beforeXHR: (xhr) => {
            xhr.setRequestHeader('Content-Type', 'application/json');
        },
        beforeSend: (settings) => {
            settings.data = JSON.stringify({
                kindPlaceId: 0,
                key: settings.urlData.query,
                _token: csrfToken
            });
            return settings
        },
        onResponse: (response) => {
            let result = [];
            let success = false;
            for(let i = 0; i < response[1].length; i++){
                success = true;
                let name;
                let place = response[1][i];
                if(place['mode'] == 'state')
                    name = ' استان ' + place['targetName'];
                else if(place['mode'] == 'city')
                    name = ' شهر ' + place['targetName'] + ' در ' + place['stateName'];
                else
                    name = place['targetName'] + ' در ' + place["cityName"];

                result.push({
                    "name" : name,
                    "value" : place['kindPlaceId'] + '_' + place['id'],
                    "text" : name,
                })
            }
            response = {
                "success": success,
                "results": result
            };
            // Modify your JSON response into the format SUI wants
            return response
        }
    }
});

function storeVideo(_file){
    window.URL = window.URL || window.webkitURL;

    var video = document.createElement('video');
    video.preload = 'metadata';
    video.src = URL.createObjectURL(_file);
    $('#thumbnailVideoVideo').attr('src', URL.createObjectURL(_file));

    var file = _file;
    var fileReader = new FileReader();
    fileReader.onload = function() {
        var blob = new Blob([fileReader.result], {type: _file.type});
        var url = URL.createObjectURL(blob);

        video.addEventListener('loadeddata', function() {

            if(snapImage('showThumbnailMain')){
                video.currentTime = video.duration/3;
                setTimeout(function(){
                    if(snapImage('showThumbnail1')){
                        video.currentTime = (video.duration/3) * 2;
                        setTimeout(function(){
                            if(snapImage('showThumbnail2')){
                                video.currentTime = video.duration - 1;
                                setTimeout(function(){
                                    snapImage('showThumbnail3');
                                    window.URL.revokeObjectURL(video.src);
                                }, 1000)
                            }
                        }, 1000);
                    }
                }, 1000);
            }
        });

        var snapImage = function(_result) {
            if(canvas == 0)
                canvas = document.createElement('canvas');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            var image = canvas.toDataURL();
            var success = image.length > 100000;

            if (success) {
                $('.' + _result).attr('src', image);
                if(_result == 'showThumbnailMain') {
                    $('.showThumbnail0').attr('src', image);
                    thumbnail = image;
                }
            }
            return success;
        };
        video.preload = 'metadata';
        video.src = url;
        video.muted = true;
        video.playsInline = true;
        video.play();
    };
    fileReader.readAsArrayBuffer(_file);

    $('#uploadVideoDiv').hide();
    $('#videoSetting').show();

    let formData = new FormData();
    formData.append('video', _file);
    formData.append('code', videoCode);
    formData.append('kind', 'video');
    formData.append('_token', csrfToken);

    storeVideoAjax = $.ajax({
        type: 'post',
        url: storeVideoURL,
        data: formData,
        processData: false,
        contentType: false,
        xhr: function () {
            let xhr = new XMLHttpRequest();
            xhr.upload.onprogress = function (e) {
                var percent = '0';
                var percentage = '0%';

                if (e.lengthComputable) {
                    percent = Math.round((e.loaded / e.total) * 100);
                    percentage = percent + '%';
                    $('#progressText').text(percentage);
                    $('#progressColor').css('width', percentage);

                }
            };

            return xhr;
        },
        success: function(response){
            try{
                response = JSON.parse(response);
                if(response['status'] == 'ok') {
                    $('#progressStatus').text('ویدیوی شما با موفقیت بارگزاری شد');
                    $('#duration').val(response['duration']);
                    uploadCompleted = true;
                }
                else
                    errorUploadVideo();
            }
            catch (e) {
                console.log(e);
                errorUploadVideo();
            }
        },
        error: function(err){
            console.log(err);
            errorUploadVideo();
        }
    })
}

function errorUploadVideo(){
    $('#uploadVideoDiv').show();
    $('#videoSetting').hide();

    openErrorAlert('بارگزاری ویدیو با مشکلی مواجه شد لطفا دوباره تلاش کنید');
}

function storeInfoVideos(_state){
    let name = $('#videoName').val();
    let categoryId = $('#videoSubCategory').val();
    let description = $('#videoText').val();
    let tags = $('#videoTags').val();
    let places = $('#videoPlaceRel').val();
    let duration = $('#duration').val();
    let kind = 'setting';
    let error = false;
    let errorText = '<ul style="list-style: none">';

    if(name.trim().length == 0){
        error = true;
        errorText += '<li style="margin-bottom: 10px">برای ویدیو خود یک نام انتخاب کنید</li>';
    }

    if(categoryId == 0){
        error = true;
        errorText += '<li>لطفا زیر دسته بندی ویدیوی خود را مشخص کنید</li>';
    }

    if(!uploadCompleted){
        error = true;
        errorText += '<li>ویدیوی شما بارگذاری نشده است. لطفا تا بارگذاری کامل صبر کنید.</li>';
    }

    if(error){
        errorText += '</ul>';
        openWarning(errorText);
        return;
    }
    else{
        let settingFormData = new FormData();
        settingFormData.append('_token', csrfToken);
        settingFormData.append('code', videoCode);
        settingFormData.append('name', name);
        settingFormData.append('kind', kind);
        settingFormData.append('places', places);
        settingFormData.append('categoryId', categoryId);
        settingFormData.append('description', description);
        settingFormData.append('tags', tags);
        settingFormData.append('duration', duration);
        settingFormData.append('state', _state);
        settingFormData.append('thumbnail', thumbnail);

        openLoading();
        $.ajax({
            type: 'post',
            url: storeVideoInfoURL,
            data: settingFormData,
            processData: false,
            contentType: false,
            success: function(response){
                try{
                    response = JSON.parse(response);
                    if(response['status'] == 'ok')
                        location.href = response['url'];
                    else{
                        console.log(response);
                        closeLoading();
                        errorUploadSettingVideo();
                    }
                }
                catch(e){
                    console.log(e);
                    closeLoading();
                    errorUploadSettingVideo();
                }
            },
            error: function(err){
                console.log(err);
                closeLoading();
                errorUploadSettingVideo();
            }
        })
    }
}

function selectNewThumbnail(_num, _element){
    $('.thumbnailSelectImgChoose').removeClass('thumbnailSelectImgChoose');
    $(_element).addClass('thumbnailSelectImgChoose');

    if(_num == 4)
        $('#newThumbnailModal').css('display', 'flex');
    else{
        thumbnail = $(_element).attr('src');
        $('.showThumbnailMain').attr('src', thumbnail);
    }
}

function cropVideoPic(){
    let videoThumbnailDiv = document.getElementById('thumbnailVideoVideo');
    var canvasThumbnail = document.getElementById('resultThumbnail');
    canvasThumbnail.width = videoThumbnailDiv.videoWidth;
    canvasThumbnail.height = videoThumbnailDiv.videoHeight;
    canvasThumbnail.getContext('2d').drawImage(videoThumbnailDiv, 0, 0, canvasThumbnail.width, canvasThumbnail.height);
    newThumbnailCrop = canvasThumbnail.toDataURL();
}

function setCropThumbnail(){
    $('.showThumbnailMain').attr('src', newThumbnailCrop);
    thumbnail = newThumbnailCrop;

    $('.thumbnailSelectImgChoose').removeClass('thumbnailSelectImgChoose');
    $('#creatCropThumbnailDiv').addClass('thumbnailSelectImgChoose');

    $('#newThumbnailModal').css('display', 'none');
}

function errorUploadSettingVideo(){
    openErrorAlert('ثبت ویدیو با مشکلی مواجه شد لطفا دوباره تلاش کنید');
}

function inputVideo(_input){
    if(_input.files[0]['type'].includes('video/'))
        storeVideo(_input.files[0]);
}

function cancelUploadVideo(){
    storeVideoAjax.abort();
    $('#videoFile').val('');

    $('#uploadVideoDiv').show();
    $('#videoSetting').hide();
}
