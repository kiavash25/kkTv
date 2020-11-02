<link rel="stylesheet" href="{{URL::asset('css/component/alertPage.css?v='.$fileVersion)}}">

<div id="alertBoxDiv" class="alertDarkBack">
    <div class="alertBox">
        <div class="alertTitle warningTitle">
            درخواست شما با مشکل مواجه شد
        </div>
        <div class="alertDescriptionBox">
            <div id="alertBodyDiv" class="alertDescription"></div>
            <div>
                <button class="alertBtn leftBtn" onclick="closeErrorAlert()">متوجه شدم</button>
            </div>
        </div>
    </div>
</div>

<div id="warningBoxDiv" class="alertDarkBack">
    <div class="alertBox">
        <div class="alertTitle offerTitle">
            {{__('یک لحظه درنگ کنید')}}
        </div>
        <div class="alertDescriptionBox">
            <div id="warningBody" class="alertDescription"></div>
            <div style="display: flex; justify-content: flex-end; align-items: center">
                <button id="warningModalCallBackShow" class="alertBtn rightBtn" onclick="cancelWarning()" style="display: none; color: #761c19; background: white;">فعلا، نه</button>
                <button id="warningOkText" class="alertBtn leftBtn" onclick="closeWarning()"></button>
            </div>
        </div>
    </div>
</div>

<div id="successNotifiAlert" class="notifAlert"></div>

<script>
    let alertWarningCallBack = false;

    function showSuccessNotifi(_msg, _side = 'right', _color = '#0076ac'){
        $('#successNotifiAlert').text(_msg);
        $('#successNotifiAlert').addClass('topAlert');

        $('#successNotifiAlert').css('background', _color);

        if(_side == 'right')
            $('#successNotifiAlert').addClass('rightAlert');
        else
            $('#successNotifiAlert').addClass('leftAlert');

        setTimeout(function(){
            $('#successNotifiAlert').removeClass('topAlert');
            setTimeout(function () {
                $('#successNotifiAlert').removeClass('leftAlert');
                $('#successNotifiAlert').removeClass('rightAlert');
            }, 1000);
        }, 5000);

    }

    function openWarning(_text, _callBack = false, _okText = 'بسیار خب'){
        alertWarningCallBack = _callBack;
        $('#warningOkText').text(_okText);

        if(typeof _callBack === 'function')
            $('#warningModalCallBackShow').show();
        else
            $('#warningModalCallBackShow').hide();

        $('#warningBody').html(_text);
        $('#warningBoxDiv').css('display', 'flex');
    }

    cancelWarning = () => $('#warningBoxDiv').css('display', 'none');

    function closeWarning(){
        if(alertWarningCallBack !== false && typeof alertWarningCallBack === 'function')
            alertWarningCallBack();
        cancelWarning()
    }

    function openErrorAlert(_text){
        $('#alertBodyDiv').html(_text);
        $('#alertBoxDiv').css('display', 'flex');
    }

    function closeErrorAlert(){
        $('#alertBoxDiv').css('display', 'none');
    }
</script>
