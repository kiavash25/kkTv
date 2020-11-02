@extends('layout.mainLayout')


@section('head')

    <link rel="stylesheet" type="text/css" href="{{asset('pack/semanticUi/semantic.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/pages/uploadVideoVod.css')}}">
    <script src="{{asset('pack/semanticUi/semantic.js')}}"></script>

    <style>
        .chooseUserCategoryInput .newCat > button.loading{
            display: block;
            background-image: url("{{URL::asset('images/mainPics/gear.svg')}}");
            background-size: 20px;
            width: 26px;
            background-repeat: no-repeat;
            background-position: center;
            cursor: not-allowed;
        }
        .rowTitle{
            color: var(--koochita-yellow);
            font-size: 20px;
            margin-top: 10px;
            margin-bottom: 15px;
            border-top: solid 1px #232323;
            padding-top: 10px;
        }
    </style>
@endsection

@section('body')
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

                <div class="col-md-4" style="display: flex; justify-content: center; align-items: center; flex-direction: column">
                    <div class="videoProgressPicDiv">
                        <img class="resizeImgClass showThumbnailMain" src="" style="width: 100%;" onload="resizeThisImg(this)">
                    </div>
                    <div class="thumbnailButton">
                        <button onclick="selectNewThumbnail(4, this)">برش عکس</button>
                        <label for="thumbnailInput">اپلود عکس</label>
                        <input type="file" accept="image/*" id="thumbnailInput" style="display: none" onchange="uploadThumbnailPic(this)">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="videoName" class="inputVideoLabel importantIcon"> عنوان ویدیو </label>
                                <input type="text" class="form-control" id="videoName">
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
                        <div class="col-md-12 rowTitle">
                            دسته بندی ویدیو
                        </div>

                        <div class="col-md-4">
                            <label for="videoCategory" class="inputVideoLabel importantIcon">دسته بندی اصلی ویدیو</label>
                            <div id="videoCategory" class="ui fluid search normal selection dropdown" style="display: flex">
                                <input type="hidden" id="mainCategory">
                                <div class="default text" style="right: 0;">انتخاب دسته بندی</div>
                                <i class="dropdown icon" style="float: left; left: 1em; right: auto;"></i>
                                <div class="menu">
                                    <div class="ui search icon input">
                                        <i class="search icon" style="left: 0px; right: auto;"></i>
                                        <input type="text" name="search" placeholder="جستجوی دسته بندی" style=" padding-right: 10px !important; text-align: right;">
                                    </div>
                                    @foreach($categories as $item)
                                        @foreach($item->sub as $sub)
                                            <div class="item" data-value="{{$sub->id}}" data-text="{{$sub->name}}">{{$sub->name}}</div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="sideVideoCategory" class="inputVideoLabel">دسته بندی فرعی ویدیو(حداکثر 3 دسته بندی)</label>
                            <div id="sideVideoCategory" class="ui fluid multiple search normal selection dropdown rtlMultiSelect" multiple="">
                                <input type="hidden" id="sideCategory">
                                <div class="default text" style="right: 0;">انتخاب دسته بندی</div>
                                <i class="dropdown icon" style="float: left; left: 1em; right: auto;"></i>
                                <div class="menu">
                                    <div class="ui search icon input">
                                        <i class="search icon" style="left: 0px; right: auto;"></i>
                                        <input type="text" name="search" placeholder="جستجوی دسته بندی" style=" padding-right: 10px !important; text-align: right;">
                                    </div>
                                    @foreach($categories as $item)
                                        @foreach($item->sub as $sub)
                                            <div class="item" data-value="{{$sub->id}}" data-text="{{$sub->name}}">{{$sub->name}}</div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12 rowTitle">
                            دسته بندی شخصی ویدیو
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userVideoCategory" class="inputVideoLabel">دسته بندی شخصی</label>
                                <div id="userVideoCategory" class="chooseUserCategoryInput" data-value="0">
                                    <div class="choosed downArrowIconAfter" onclick="toggleAddAbleSelectList('userVideoCategory')">بدون دسته بندی</div>
                                    <div class="list">
                                        <div class="newCat">
                                            <input type="text" id="newYourCategoryInput" placeholder="دسته بندی جدید">
                                            <button class="plusIcon" onclick="storeNewYourCategory(this)"></button>
                                        </div>
                                        <div class="yourCat">
                                            <div class="cats selected" data-value="0" onclick="chooseAddAbleSelect(this, 'userVideoCategory')">بدون دسته بندی</div>
                                            @foreach($userCategories as $item)
                                                <div class="cats" data-value="{{$item->id}}" onclick="chooseAddAbleSelect(this, 'userVideoCategory')">{{$item->name}}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="userPlayList" class="inputVideoLabel">لیست پخش</label>
                                <div id="userPlayList" class="chooseUserCategoryInput" data-value="0">
                                    <div class="choosed downArrowIconAfter" onclick="toggleAddAbleSelectList('userPlayList')">بدون لیست پخش</div>
                                    <div class="list">
                                        <div class="newCat">
                                            <input type="text" id="newPlayListInput" placeholder="لیست پخش جدید">
                                            <button class="plusIcon" onclick="storeNewPlayList(this)"></button>
                                        </div>
                                        <div class="yourCat">
                                            <div class="cats selected" data-value="0" onclick="chooseAddAbleSelect(this, 'userPlayList')">بدون لیست پخش</div>
                                            @foreach($userPlayList as $item)
                                                <div class="cats" data-value="{{$item->id}}" onclick="chooseAddAbleSelect(this, 'userPlayList')">{{$item->name}}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 rowTitle"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="videoTags" class="inputVideoLabel">برچسپ ها</label>
                                <select id="videoTags" name="videoTags" class="ui fluid search dropdown rtlMultiSelect" multiple=""></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="videoPlaceRel" class="inputVideoLabel">ویدیوی شما مربوط به شهر و یا مکان خاصی می شود؟</label>
                                <select id="videoPlaceRel" name="videoPlaceRel" class="ui fluid search dropdown rtlMultiSelect" multiple=""></select>
                            </div>
                        </div>
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="thumb" class="inputVideoLabel">عکس پیش نمایش</label>--}}
{{--                                <div id="thumb" class="thumbnailSelectDiv">--}}
{{--                                    <div class="thumbnailSelectImgDiv">--}}
{{--                                        <img src="" class="showThumbnail0 thumbnailSelectImg thumbnailSelectImgChoose" onclick="selectNewThumbnail(0, this)">--}}
{{--                                    </div>--}}
{{--                                    <div class="thumbnailSelectImgDiv">--}}
{{--                                        <img src="" class="showThumbnail1 thumbnailSelectImg" onclick="selectNewThumbnail(1, this)">--}}
{{--                                    </div>--}}
{{--                                    <div class="thumbnailSelectImgDiv">--}}
{{--                                        <img src="" class="showThumbnail2 thumbnailSelectImg" onclick="selectNewThumbnail(2, this)">--}}
{{--                                    </div>--}}
{{--                                    <div class="thumbnailSelectImgDiv">--}}
{{--                                        <img src="" class="showThumbnail3 thumbnailSelectImg" onclick="selectNewThumbnail(3, this)">--}}
{{--                                    </div>--}}
{{--                                    <div id="creatCropThumbnailDiv" class="thumbnailSelectImgDiv newThumbnailChoose" onclick="selectNewThumbnail(4, this)">--}}
{{--                                        انتخاب عکس--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>

                    <div class="row">
                        <div class="buttonDiv">
                            {{--<div class="saveButton notReleaseButton" onclick="storeInfoVideos(0)">--}}
                            {{--                                ذخیره شود و بعدا منتشر می کنم--}}
                            {{--</div>--}}
                            <div class="saveButton releaseButton" onclick="storeInfoVideos(1)"> انتشار ویدیو </div>
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
                <video id="thumbnailVideoVideo" src="" style="width: 400px; max-height: 40vh; max-width: 100%" controls muted></video>
                <button class="cropButton" onclick="cropVideoPic()">برش تصویر</button>
            </div>

            <div class="row" style="display: flex; justify-content: center; align-items: center; flex-direction: column; max-height: 50vh;">
                <canvas id="resultThumbnail" class="resultThumbnail"></canvas>
                <button class="btn btn-success submitCropButton" onclick="setCropThumbnail()">تایید</button>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        // $('#uploadVideoDiv').hide();
        // $('#videoSetting').show();

        var newCategoryUrl = '{{route("video.yourCategory.new")}}';
        var newPlayListUrl = '{{route("playList.new")}}';
        let totalSearchURL = "{{route('ajax.totalPlaceSearch')}}";
        let getTagsURL = "{{route('ajax.getTags')}}";
        let storeVideoURL = '{{route("video.uploadVideoFile")}}';
        let storeVideoInfoURL = '{{route("video.storeVideoInfo")}}';
        let deleteUploadedVideoUrl = '{{route("video.uploadFile.delete")}}';

        $('#sideVideoCategory').dropdown({ maxSelections: 3 });
        $('#videoCategory').dropdown({ clearable: true });

        function storeInfoVideos(_state){
            let name = $('#videoName').val();
            let description = $('#videoText').val();
            let mainCategory = $('#mainCategory').val();
            let sideCategory = $('#sideCategory').val();

            let userCategory = $('#userVideoCategory').attr('data-value');
            let userPlayList = $('#userPlayList').attr('data-value');

            let tags = $('#videoTags').val();
            let places = $('#videoPlaceRel').val();
            let duration = $('#duration').val();

            let kind = 'setting';
            let error = false;
            let errorText = '<ul style="font-size: 16px; font-weight: 400; text-align: right; line-height: normal;">';

            if(name.trim().length == 0){
                error = true;
                errorText += '<li>برای ویدیو خود یک عنوان انتخاب کنید</li>';
            }

            if(!mainCategory > 0){
                error = true;
                errorText += '<li>لطفا دسته بندی ویدیوی خود را مشخص کنید</li>';
            }

            if(uploadFileName == null){
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
                settingFormData.append('_token', window.csrfTokenGlobal);
                settingFormData.append('name', name);
                settingFormData.append('description', description);
                settingFormData.append('fileName', uploadFileName);
                settingFormData.append('kind', kind);
                settingFormData.append('mainCategory', mainCategory);
                settingFormData.append('sideCategory', sideCategory);
                settingFormData.append('userCategory', userCategory);
                settingFormData.append('userPlayList', userPlayList);
                settingFormData.append('tags', tags);
                settingFormData.append('places', places);
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
                            if(response.status == 'ok')
                                location.href = response.url;
                            else{
                                console.log(response);
                                closeLoading();
                                openErrorAlert('در هنگام ثبت ویدیو مشکلی پیش امده.');
                            }
                        }
                        catch(e){
                            console.log(e);
                            closeLoading();
                            openErrorAlert('در هنگام ثبت ویدیو مشکلی پیش امده.');
                        }
                    },
                    error: err => {
                        console.log(err);
                        openErrorAlert('در هنگام ثبت ویدیو مشکلی پیش امده.');
                    }
                })
            }
        }

    </script>

    <script src="{{URL::asset('js/uploadLargFile.js')}}"></script>
    <script src="{{URL::asset('js/pages/uploadVideoVod.js')}}"></script>

@endsection
