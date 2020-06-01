@extends('streaming.layout.streamingLayout')


@section('head')

    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/streaming/uploadVideoVod.css')}}">

    <style>
        .uploadLaw{
            margin-top: 10px;
            background: #3a3a3a;
            border-radius: 8px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 16px;
            text-align: justify;
            padding: 50px 15px;

        }
    </style>
    <script src="{{asset('semanticUi/semantic.js')}}"></script>
@endsection

@section('body')

    <input type="hidden" id="code" name="code" value="{{$code}}">
    <input type="hidden" id="duration" name="duration">

    <div class="container uploadBase">
        <input type="file" id="videoFile" accept="video/*" style="display: none" onchange="inputVideo(this)">
        <label for="videoFile" id="uploadVideoDiv" class="uploadDiv" style="display: block">
            <div class="row" style="width: 100%; margin: 0">
{{--                <div class="col-md-6"></div>--}}
                <div class="col-md-12 uploadText" >
                    <div>
                        ویدیو خود را در اینجا قرار دهید
                    </div>
                    <div style="font-size: 15px; font-weight: 500; margin-top: 16px;">
                        و یا
                    </div>
                    <div class="clickUpload">
                        کلیک کنید
                    </div>
                </div>
            </div>
        </label>

        <div id="videoSetting" class="videoSetting" style="display: none">
            <div class="row videoUploadProgressDiv">

                <div class="col-md-4">
                    <div class="videoProgressPicDiv">
                        <img class="showThumbnailMain" src="" style="height: 150px; border-radius: 10px">
                    </div>
                </div>
                <div class="col-md-8 progressDiv">
                    <div class="progressStatus">
                        <div id="progressStatus">
                            ویدیو شما در حال بارگذاری می باشد
                        </div>
                        <div class="cancelUpload" onclick="cancelUploadVideo()">
                            لغو آپلود
                        </div>
                    </div>
                    <div class="progressBar">
                        <div id="progressColor" class="progressColor"></div>
                        <div id="progressText" class="progressText">0%</div>
                    </div>
                </div>
            </div>

            <div class="row videoInfos">
                <div class="col-md-12 warningText">
                    برای بارگذاری محتوا باید موارد ستاره دار را پر کنید.
                </div>
                <div class="col-md-12">
                    <div class="row" style="display: flex;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="videoName" class="inputVideoLabel importantIcon">
                                    نام ویدیو
                                </label>
                                <input type="text" class="form-control" id="videoName">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="videoCategory" class="inputVideoLabel importantIcon">دسته بندی ویدیو</label>
                                <select name="videoCategory" id="videoCategory" class="form-control" onchange="changeCategoryVideo(this.value)">
                                    <option id="zeroValue" value="0">...</option>
                                    @foreach($categories as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="subCategorySection" class="col-md-3" style="display: none">
                            <div class="form-group">
                                <label for="videoSubCategory" class="inputVideoLabel importantIcon">زیر دسته بندی ویدیو</label>
                                <select name="videoSubCategory" id="videoSubCategory" class="form-control">
                                    <option value="0" selected></option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="videoText" class="inputVideoLabel">توضیحات ویدیو (اختیاری)</label>
                                <textarea class="form-control" id="videoText" name="videoText" rows="5" style="width: 100%;"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="videoTags" class="inputVideoLabel">برچسپ ها</label>
                                <select id="videoTags" name="videoTags" class="ui fluid search dropdown rtlMultiSelect" multiple=""></select>
                            </div>
                            <div class="form-group">
                                <label for="videoPlaceRel" class="inputVideoLabel">ویدیوی شما مربوط به شهر و یا مکان خاصی می شود؟</label>
                                <select id="videoPlaceRel" name="videoPlaceRel" class="ui fluid search dropdown rtlMultiSelect" multiple=""></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="thumb" class="inputVideoLabel">عکس پیش نمایش</label>
                                <div id="thumb" class="thumbnailSelectDiv">
                                    <div class="thumbnailSelectImgDiv">
                                        <img src="" class="showThumbnail0 thumbnailSelectImg thumbnailSelectImgChoose" onclick="selectNewThumbnail(0, this)">
                                    </div>
                                    <div class="thumbnailSelectImgDiv">
                                        <img src="" class="showThumbnail1 thumbnailSelectImg" onclick="selectNewThumbnail(1, this)">
                                    </div>
                                    <div class="thumbnailSelectImgDiv">
                                        <img src="" class="showThumbnail2 thumbnailSelectImg" onclick="selectNewThumbnail(2, this)">
                                    </div>
                                    <div class="thumbnailSelectImgDiv">
                                        <img src="" class="showThumbnail3 thumbnailSelectImg" onclick="selectNewThumbnail(3, this)">
                                    </div>
                                    <div id="creatCropThumbnailDiv" class="thumbnailSelectImgDiv newThumbnailChoose" onclick="selectNewThumbnail(4, this)">
                                        انتخاب عکس
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="buttonDiv">
{{--                            <div class="saveButton notReleaseButton" onclick="storeInfoVideos(0)">--}}
{{--                                ذخیره شود و بعدا منتشر می کنم--}}
{{--                            </div>--}}
                            <div class="saveButton releaseButton" onclick="storeInfoVideos(1)">
                                انتشار ویدیو
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container uploadLaw">
        <div class="row">
            <div class="col-md-6">
                در سیستم بارگذاری کوچیتا ، برای بارگذاری ویدئوهای خود از آخرین نسخه مرورگرهای به روز همچون کروم ، فایرفاکس ، سافاری استفاده نمایید.
            </div>
            <div class="col-md-6">
                به جهت حفظ حقوق مؤلفین و رونق تجاری سینمای کشور، لطفاً از بارگذاری ویدیوهایی که دارای حق نشر می باشند و در شبکه نمایش خانگی به فروش می رسند، خودداری فرمایید.
            </div>
        </div>
    </div>

    <div id="newThumbnailModal" class="ui_backdrop dark newThumbnailModal">
        <div class="selectThumbnailDiv">
            <div class="row selectThumbnailDivVideoSection">
                <div class="closeDivVideoSection" onclick="$('#newThumbnailModal').css('display', 'none')"></div>
                <video id="thumbnailVideoVideo" src="" style="width: 400px" controls muted></video>
                <button onclick="cropVideoPic()" style="margin-top: 20px">برش تصویر</button>
            </div>

            <div class="row" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                <canvas id="resultThumbnail" class="resultThumbnail"></canvas>
                <button class="btn btn-success" onclick="setCropThumbnail()">تایید</button>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        let getTagsURL = "{{route('getTags')}}";
        let totalSearchURL = "{{route('totalSearch')}}";
        let storeVideoURL = '{{route("streaming.storeVideo")}}';
        let storeVideoInfoURL = '{{route("streaming.storeVideoInfo")}}';
        let csrfToken = "{{csrf_token()}}";
        let categoryies = {!! $categories !!};

        let thumbnail = '';
        let newThumbnailCrop;
        let uploadCompleted = false;
        let canvas = 0;

        let videoDropZone = $('#uploadVideoDiv');
        let videoCode = $('#code').val();

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

        function changeCategoryVideo(_value){
            $('#zeroValue').remove();
            if(_value != 0){
                let cat = null;
                categoryies.forEach(item => {
                    if(item.id == _value)
                        cat = item;
                });

                if(cat != null){
                    let option = '<option value="0" selected>...</option>';
                    cat.sub.forEach(item => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#videoSubCategory').html(option);
                    $('#subCategorySection').show();
                }
                else {
                    $('#subCategorySection').hide();
                    $('#videoSubCategory').val(0);
                }
            }
            $('#videoSubCategory').val(0);
        }
    </script>

    <script src="{{URL::asset('js/stream/uploadVideoVod.js')}}"></script>
@endsection