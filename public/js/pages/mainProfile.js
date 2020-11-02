createVideoSuggestionDiv(allUserVideos, 'allVideo', () => $('#allVideo').find('.videoSuggestion').addClass('videoInList'), true);
createVideoSuggestionDiv(allVideoBookMark, 'allBookMarkedVideo', () => $('#allBookMarkedVideo').find('.videoSuggestion').addClass('videoInList'), true);
$('#allPlayListDiv').html(createPlayListObjGroups(showAblePlayList) /**in playListObj.blade.php**/);

function openAllVideoSelectModal(_header, _onSubmit, _selectType = 'single', _selectedId = []){
    $('#inputForSearchInAllVideos').val('');
    $('#allVideoModal').find('.title').text(_header);
    submitAllVideoModalCallBack = _onSubmit;
    selectedTypeInAllVideoModal = _selectType;

    createTopVideoElement(allUserVideos, _selectedId);

    openMyModal('allVideoModal');
    resizeFitImg('resizeImgClass');
}

function createTopVideoElement(_result, _selectedId){
    var text = '';
    var selIds = [];
    _selectedId.map(item => selIds.push(String(item)));
    _result.map(item => {
        text += `<div id="allVideoResultModal_${item.id}" data-value="${item.id}" class="videoResult showLineOfText line_2 ${selIds.indexOf(String(item.id)) > -1 ? 'selected' : ''}">
                            <div class="picSec">
                                <img src="${item.pic}" class="resizeImgClass" onload="resizeThisImg(this)">
                            </div>
                            <div class="name">${item.title}</div>
                        </div>`;
    });
    $('#bodyForAllVideoModalSearch').html(text);

    $('#bodyForAllVideoModalSearch .videoResult').on('click', e => {
        var elem = $(e.target);
        while(!elem.hasClass('videoResult'))
            elem = elem.parent();

        if(selectedTypeInAllVideoModal == 'single')
            $('#bodyForAllVideoModalSearch .videoResult.selected').removeClass('selected');

        elem.toggleClass('selected');
    });
}

function searchForVideoInAllVideosModal(_value){
    if(_value.trim().length > 0) {
        $('#bodyForAllVideoModalSearch .videoResult').css('display', 'none');
        _value = _value.trim();
        allUserVideos.map(item => {
            if(item.title.search(_value) > -1)
                $(`#allVideoResultModal_${item.id}`).css('display', 'flex');
        });
    }
    else
        $('#bodyForAllVideoModalSearch .videoResult').css('display', 'flex');
}

function submitAllVideoModal(){
    var selectedId = [];
    var selectedElement = $('#bodyForAllVideoModalSearch .videoResult.selected');
    for(var i = 0; i < selectedElement.length; i++)
        selectedId.push($(selectedElement[i]).attr('data-value'));

    submitAllVideoModalCallBack(selectedId);
    closeMyModal('allVideoModal');
}

function changeTab(_element, _tab){
    $('.hTab').find('.tab').removeClass('selected');
    $(_element).addClass('selected');

    $('.tabBody').addClass('hidden');
    $(`#${_tab}Tab`).removeClass('hidden');

    resizeFitImg('resizeImgClass');
    resizeRows('videoInList');
}

function openPlayListEditModal(){
    createPlayListEditModalRow(allUserPlayList);
    openMyModal('playListEditModal');
}

function openEditPlayListFunc(_element, _kind){
    var parent = $(_element).parent();
    while(!parent.hasClass('playListRowModal'))
        parent = parent.parent();

    if(_kind == 'open') {
        parent.find('.mainEditIcons').addClass('hidden');
        parent.find('.playListName').addClass('hidden');

        parent.find('.editesBtn').removeClass('hidden');
        parent.find('.playListNameInput').removeClass('hidden');
    }
    else{
        parent.find('.mainEditIcons').removeClass('hidden');
        parent.find('.playListName').removeClass('hidden');

        parent.find('.editesBtn').addClass('hidden');
        parent.find('.playListNameInput').addClass('hidden');
    }
}

function deletePlayList(_playListId){
    deletedPlayListId = _playListId;
    openWarning('آیا از حذف لیست پخش اطمینان دارید؟', doDeletePlayList, 'بله پاک شود');
}

function doDeletePlayList(){
    openLoading();
    $.ajax({
        type: 'post',
        url: window.deletePlayListUrl,
        data:{
            _token: window.csrfTokenGlobal,
            id: deletedPlayListId
        },
        success: response => {
            closeLoading();
            if(response.status == 'ok'){
                allUserPlayList = response.result;
                openPlayListEditModal();
                showSuccessNotifi('لیست پخش حذف شد', 'left', 'var(--koochita-blue)');
            }
            else
                showSuccessNotifi('مشکلی در حذف لیست پخش پیش امده', 'left', 'red');
        },
        error: err => {
            console.log(err);
            closeLoading();
            showSuccessNotifi('مشکلی در حذف لیست پخش پیش امده', 'left', 'red');
        }
    })
}

function addNewVideoToPlayList(_playListIndex){
    var selectedId = [];
    allUserPlayList[_playListIndex].videos.map(item => selectedId.push(item.id));
    selectVideoForPlayListId = allUserPlayList[_playListIndex].id;
    openAllVideoSelectModal('ویرایش ویدیو های داخل لیست پخش', handleSubmitChangePlayListVideo, 'multi', selectedId)
}

function handleSubmitChangePlayListVideo(_ids){
    openLoading();
    if(_ids.length == 0)
        _ids = [0];

    $.ajax({
        type: 'post',
        url: window.editVideoListInPlayList,
        data: {
            _token: window.csrfTokenGlobal,
            playListId: selectVideoForPlayListId,
            videosId: _ids,
        },
        success: function(response){
            closeLoading();
            if(response.status == 'ok'){
                allUserPlayList = response.result;
                openPlayListEditModal();
                showSuccessNotifi('لیست پخش به روز شد', 'left', 'var(--koochita-blue)');
            }
            else
                showSuccessNotifi('مشکلی در به روزرسانی لیست پخش پیش امده', 'left', 'red');
        },
        error: err =>{
            closeLoading();
            console.log(err);
            showSuccessNotifi('مشکلی در به روزرسانی لیست پخش پیش امده', 'left', 'red');
        }
    })
}

function submitChangePlayListName(_element){
    var parent = $(_element).parent();
    while(!parent.hasClass('playListRowModal'))
        parent = parent.parent();

    var id = parent.attr('data-value');
    var value = parent.find('.playListNameInput').val();
    if(value.trim().length > 0){
        openLoading();
        $.ajax({
            type: 'post',
            url: window.editPlayListNameUrl,
            data: {
                _token: window.csrfTokenGlobal,
                id: id,
                text: value.trim()
            },
            success: response => {
                closeLoading();
                if(response.status == 'ok'){
                    openEditPlayListFunc(parent.find('.playListNameInput'), 'close');
                    updatePlayListName(id, value.trim());
                    showSuccessNotifi('نام لیست پخش به روز شد', 'left', 'var(--koochita-blue)');
                }
                else if(response.status == 'duplicate')
                    showSuccessNotifi('نام انتخاب شده تکراری می باشد', 'left', 'red');
                else
                    showSuccessNotifi('مشکلی در بروز رسانی پیش امده', 'left', 'red');
            },
            error: err => {
                closeLoading();
                console.error(err);
                showSuccessNotifi('مشکلی در بروز رسانی پیش امده', 'left', 'red');
            }
        })
    }
}

function updatePlayListName(_id, _name){
    allUserPlayList.map(item => {
        if(item.id == _id)
            item.name = _name;
    });

    createPlayListEditModalRow(allUserPlayList);
}

function createPlayListEditModalRow(_result){

    var text = '';
    _result.map((item, playListIndex) =>{
        var videoText = '';
        item.videos.map((vid, vidIndex) => {
            videoText += `<div id="vidRowInPlayListModal_${vid.id}" class="vidRow" data-value="${vid.id}" draggable="true">
                                    <div class="dragRow">
                                        <div class="threeDotHorizontalIcon"></div>
                                        <div class="threeDotHorizontalIcon"></div>
                                        <div class="threeDotHorizontalIcon"></div>
                                    </div>
                                    <div class="name">${vid.title}</div>
                                    <div class="closeIcon" onclick="deleteVideoFromPlayList(${vidIndex}, ${playListIndex})"></div>
                                </div>`;
        });

        text += ` <div class="playListRowModal" data-value="${item.id}">
                            <div class="infos">
                                <div class="name">
                                    <div class="playListName text showLineOfText line_1">${item.name}</div>
                                    <input type="text" class="playListNameInput hidden" value="${item.name}">
                                </div>
                                <div class="bts">
                                    <div class="editesBtn hidden">
                                        <button class="tickIcon" onclick="submitChangePlayListName(this)"></button>
                                        <button class="closeIcon" onclick="openEditPlayListFunc(this, 'close')"></button>
                                    </div>
                                    <div class="mainEditIcons" style="display: flex; align-items: center;">
                                        <div class="addButton plusIcon" onclick="addNewVideoToPlayList(${playListIndex})" style="margin-right: 20px;"></div>
                                        <div class="addButton editIcon" onclick="openEditPlayListFunc(this, 'open')"></div>
                                        <div class="addButton trashIcon" onclick="deletePlayList(${item.id})"></div>
                                    </div>
                                </div>
                                <div class="moreBtn downArrowIcon" onclick="openVideoListOfPlayListModal(this)"></div>
                            </div>
                            <div class="videos videosRowOfPlayList">${videoText}</div>
                        </div>`;
    });

    $('#playListEditBodyModal').html(text);

    $('.playListRowModal').on('click', e => {
        if($(e.target).hasClass('infos'))
            openVideoListOfPlayListModal(e.target);
    });

    $(".videosRowOfPlayList").sortable({
        update: (e, ui) =>{
            var parent = $(e.target).parent();
            while(!parent.hasClass('playListRowModal'))
                parent = parent.parent();

            var id = parent.attr('data-value');

            var updatedId = [];
            allUserPlayList.map(item => {
                if(item.id == id){
                    var childs = $(e.target).children();
                    for(let i = 0; i < childs.length; i++)
                        updatedId.push($(childs[i]).attr('data-value'));

                    var newViiid = [];
                    var viids = item.videos;
                    updatedId.map(newId => {
                        viids.map(oldVids => {
                            if(oldVids.id == newId)
                                newViiid.push(oldVids);
                        })
                    });

                    item.videos = newViiid;
                    updatedPlayListVideosSortInBackend(item.id, updatedId);
                }
            });
        }
    });
    $(".videosRowOfPlayList").disableSelection();
}

function updatedPlayListVideosSortInBackend(_id, _newSort){
    $.ajax({
        type: 'post',
        url: window.editPlayListVideoSortUrl,
        data: {
            _token: window.csrfTokenGlobal,
            id: _id,
            newSort: _newSort
        },
        success: response => console.log(response),
        error: err => console.log(err),
    })
}

function openVideoListOfPlayListModal(_element){
    var parent = $(_element).parent();
    while(!parent.hasClass('playListRowModal'))
        parent = parent.parent();

    parent.find('.videos').toggleClass('show');
}

function deleteVideoFromPlayList(_videoIndex, _index){
    var _id = allUserPlayList[_index].videos[_videoIndex].id;
    $.ajax({
        type: 'post',
        url: window.deleteVideoFromPlayListUrl,
        data: {
            _token: window.csrfTokenGlobal,
            id: _id
        },
        success: response => {
            if(response.status == 'ok'){
                $('#vidRowInPlayListModal_'+_id).remove();
                showSuccessNotifi('ویدیو از لیست پخش حذف شد', 'left', 'var(--koochita-blue)');
                allUserPlayList[_index].videos.splice(_videoIndex, 1);
            }
        },
        error: err => {
            console.error(err);
            showSuccessNotifi('مشکلی در حذف پیش امده', 'left', 'red');
        }
    })
}

function submitNewPlayListName(){
    var value = $('#newPlayListNameInput').val();
    if(value.trim().length > 0){
        openLoading();
        $.ajax({
            type: 'post',
            url: window.newPlayListStoreUrl,
            data: {
                _token: window.csrfTokenGlobal,
                text: value
            },
            success: response =>{
                closeLoading();
                if(response.status == 'ok'){
                    showSuccessNotifi('لیست پخش با موفقیت ثبت شد', 'left', 'var(--koochita-blue)');
                    allUserPlayList.push(response.result);
                    closeMyModal('addNewPlayListModal');
                    openPlayListEditModal();
                }
                else if(response.status == 'duplicate')
                    showSuccessNotifi('نام لیست پخش تکراری است', 'left', 'red');
            },
            error: err => {
                closeLoading();
                showSuccessNotifi('مشکلی در ثبت لیست پخش پیش امده', 'left', 'red');
                console.log(err);
            }
        })
    }
}

function editProfileBannerPic(_input){
    if(_input.files && _input.files[0]){

        openLoading(() => {
            cleanImgMetaData(_input, (imgDataURL, _file) => {
                var formData = new FormData();
                formData.append('file', _input.files[0]);

                $.ajax({
                    type: 'post',
                    url: window.updateProfileBannerPic,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: response => {
                        closeLoading();
                        if(response.status == 'ok'){
                            $('#bannerPic').attr('src', imgDataURL);
                            resizeFitImg('resizeImgClass');
                        }
                        else if(response.status == 'bigFile')
                            showSuccessNotifi('حجم عکس باید زیر 2 مگابایت باشد.', 'left', 'red');
                        else
                            showSuccessNotifi('مشکلی در بارگزاری عکس پیش امده', 'left', 'red');
                    },
                    error: err => {
                        closeLoading();
                        console.error(err);
                        showSuccessNotifi('مشکلی در بارگزاری عکس پیش امده', 'left', 'red');
                    }
                })
            })
        });
    }
}

