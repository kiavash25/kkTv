let videoDropZone = $('#uploadVideoDiv');

let thumbnail = '';
let newThumbnailCrop;
let canvas = 0;
var uploadFileName = null;
var uploadState = null;

videoDropZone.on('dragover', function() {
    videoDropZone.addClass('hover');
    return false;
});
videoDropZone.on('dragleave', function() {
    videoDropZone.removeClass('hover');
    return false;
});
videoDropZone.on('drop', function(e) {
    e.stopPropagation();
    e.preventDefault();
    var files = e.originalEvent.dataTransfer.files;
    if(files[0]['type'].includes('video/'))
        storeVideo(files[0]);
});


function toggleAddAbleSelectList(_id){
    $(`#${_id}`).find('.list').toggleClass('show');
}

function openAddAbleSelectList(){
    $('#userVideoCategory').find('.list').addClass('show');
}

function closeAddAbleSelectList(_id){
    $(`#${_id}`).find('.list').removeClass('show');
}

function chooseAddAbleSelect(_element, _id){
    $(`#${_id}`).attr('data-value', $(_element).attr('data-value'));
    $(`#${_id}`).find('.choosed').text($(_element).text());
    $(`#${_id}`).find('.selected').removeClass('selected');
    $(_element).addClass('selected');
    closeAddAbleSelectList(_id);
}

function storeNewYourCategory(_element) {
    var value = $('#newYourCategoryInput').val();
    if(value.trim().length > 0 && !$(_element).hasClass('loading')){
        $(_element).addClass('loading');
        $.ajax({
            type: 'post',
            url: newCategoryUrl,
            data: {
                _token: window.csrfTokenGlobal,
                text: value
            },
            success: response =>{
                $(_element).removeClass('loading');
                if(response.status == 'ok'){
                    showSuccessNotifi('دسته بندی با موفقیت ثبت شد', 'left', 'var(--koochita-blue)');
                    $('#userVideoCategory').find('.yourCat').append(`<div class="cats" data-value="${response.result.id}" onclick="chooseAddAbleSelect(this, 'userVideoCategory')">${response.result.name}</div>`);
                    $('#newYourCategoryInput').val('');
                }
                else if(response.status == 'duplicate')
                    showSuccessNotifi('نام دسته بندی تکراری است', 'left', 'red');
            },
            error: err => {
                $(_element).removeClass('loading');
                showSuccessNotifi('مشکلی در ثبت دسته بندی پیش امده', 'left', 'red');
                console.log(err);
            }
        })
    }
}

function storeNewPlayList(_element){
    var value = $('#newPlayListInput').val();
    if(value.trim().length > 0 && !$(_element).hasClass('loading')){
        $(_element).addClass('loading');
        $.ajax({
            type: 'post',
            url: newPlayListUrl,
            data: {
                _token: window.csrfTokenGlobal,
                text: value
            },
            success: response =>{
                $(_element).removeClass('loading');
                if(response.status == 'ok'){
                    showSuccessNotifi('لیست پخش با موفقیت ثبت شد', 'left', 'var(--koochita-blue)');
                    $('#userPlayList').find('.yourCat').append(`<div class="cats" data-value="${response.result.id}" onclick="chooseAddAbleSelect(this, 'userPlayList')">${response.result.name}</div>`);
                    $('#newPlayListInput').val('');
                }
                else if(response.status == 'duplicate')
                    showSuccessNotifi('نام لیست پخش تکراری است', 'left', 'red');
            },
            error: err => {
                $(_element).removeClass('loading');
                showSuccessNotifi('مشکلی در ثبت لیست پخش پیش امده', 'left', 'red');
                console.log(err);
            }
        })
    }
}

function inputVideo(_input){
    if(_input.files[0]['type'].includes('video/'))
        storeVideo(_input.files[0]);
}

function storeVideo(_file){
    $('#uploadVideoDiv').hide();
    $('#videoSetting').show();

    window.URL = window.URL || window.webkitURL;

    var video = document.createElement('video');
    video.preload = 'metadata';
    video.src = URL.createObjectURL(_file);
    $('#thumbnailVideoVideo').attr('src', URL.createObjectURL(_file));

    var fileReader = new FileReader();
    fileReader.onload = function() {
        var blob = new Blob([fileReader.result], {type: _file.type});
        var url = URL.createObjectURL(blob);

        video.addEventListener('loadeddata', function() {

            if(snapImage('showThumbnailMain')){
                $('#duration').val(video.duration);
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

    uploadLargeFile(storeVideoURL, _file, [], updateUploadStatus);
}

function updateUploadStatus(_percent, _fileName = ''){
    if(_percent == 'done'){
        $('#progressText').text('100%');
        $('#progressColor').css('width', '100%');
        $('#progressStatus').text('ویدیوی شما با موفقیت بارگزاری شد');
        uploadFileName = _fileName;
        uploadState = 'done';
    }
    else if(_percent == 'error'){
        $('#progressStatus').text('در بارگزاری ویدیو مشکلی پیش امده لطفا دوباره تلاش نمایید.');
        uploadState = 'error';
    }
    else if(_percent == 'cancelUpload'){
        openErrorAlert('بارگزاری ویدیو متوقف شد.');
        $('#progressText').text('0%');
        $('#videoFile').val('');
        $('#uploadVideoDiv').show();
        $('#videoSetting').hide();
        uploadState = null;
    }
    else {
        uploadState = 'process';
        $('#progressText').text(_percent + '%');
        $('#progressColor').css('width', _percent + '%');
    }
}

function cancelUploadVideo(){
    $('#progressStatus').text('ویدیوی شما در حال حذف می باشد');
    if(uploadState == 'process')
        cancelLargeUploadedFile(); // in uploadLargFile.js
    else if(uploadState == 'done')
        deleteUploadedFile();
}

function deleteUploadedFile(){

    $.ajax({
        type: 'delete',
        url: deleteUploadedVideoUrl,
        data: {
            _token: window.csrfTokenGlobal,
            fileName: uploadFileName,
        },
        success: response => {
            if(response.status == 'ok'){
                openErrorAlert('بارگزاری ویدیو متوقف شد.');
                $('#progressText').text('0%');
                $('#videoFile').val('');
                $('#uploadVideoDiv').show();
                $('#videoSetting').hide();
                uploadState = null;
            }
        },
        error: err => console.log(err)
    })
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
    resizeFitImg('resizeImgClass');
}

function uploadThumbnailPic(_input){
    if(_input.files && _input.files[0]){
        cleanImgMetaData(_input, (imgDataURL, _file) => {
            $('.showThumbnailMain').attr('src', imgDataURL);
            thumbnail = imgDataURL;
            resizeFitImg('resizeImgClass');
        })
    }
}

$('#videoTags').dropdown({
    apiSettings: {
        url: getTagsURL,
        method: 'get',
        cache: false,
        beforeXHR: (xhr) => {
            xhr.setRequestHeader('Content-Type', 'application/json');
        },
        beforeSend: (settings) => {
            settings.data = {
                tag: settings.urlData.query,
            };
            return settings
        },
        onResponse: (response) => {
            let result = [];
            if(response.same == 0) {
                result = [{
                    "name" : response.send,
                    "value": response.send,
                    "text" : response.send
                }]
            }
            else{
                result = [{
                    "name" : response.same.name,
                    "value": response.same.name,
                    "text" : response.same.name
                }]
            }
            if(response.tags.length != 0){
                for(let i = 0; i < response.tags.length; i++){
                    result.push({
                        "name"  : response.tags[i].name,
                        "value" : response.tags[i].name,
                        "text"  : response.tags[i].name
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
        method: 'get',
        cache: false,
        beforeXHR: (xhr) => {
            xhr.setRequestHeader('Content-Type', 'application/json');
        },
        beforeSend: (settings) => {
            settings.data = {
                value: settings.urlData.query,
                filter: {
                    kindPlaceId : 1,
                    state : 1,
                    city : 1,
                }
            };
            return settings
        },
        onResponse: (response) => {
            let result = [];
            let success = false;
            if(response.status == 'ok')
                response.result.map(item =>{
                    success = true;
                    let name;
                    if(item.kind == 'state')
                        name = ' استان ' + item.name;
                    else if(item.kind == 'city')
                        name = ' شهر ' + item.name + ' در ' + item.state;
                    else
                        name = item.name + ' در ' + item.city;

                    result.push({
                        "name" : name,
                        "value" : item.kindPlaceId + '_' + item.id,
                        "text" : name,
                    })
                });

            response = {
                "success": true,
                "results": result
            };
            return response;
        }
    }
});
