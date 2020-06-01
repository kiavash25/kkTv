<link rel="stylesheet" href="{{ asset('css/component/alertPage.css') }}">

<div id="alertBoxDiv" class="alertDarkBack">
    <div class="alertBox">
        <div class="alertTitle warningTitle">
            درخواست شما با مشکل مواجه شد
        </div>
        <div class="alertDescriptionBox">
            <div id="alertBodyDiv" class="alertDescription"></div>
            <div>
{{--                <button class="alertBtn rightBtn">خیر</button>--}}
                <button class="alertBtn leftBtn" onclick="closeErrorAlert()">متوجه شدم</button>
            </div>
        </div>
    </div>
</div>

<div id="warningBoxDiv" class="alertDarkBack">
    <div class="alertBox">
        <div class="alertTitle offerTitle">
            یک لحظه درنگ کنید
        </div>
        <div class="alertDescriptionBox">
            <div id="warningBody" class="alertDescription"></div>
            <div style="display: flex; justify-content: center; align-items: center">
{{--                <button class="alertBtn rightBtn" onclick="closeWarning()">فعلا، نه</button>--}}
                <button class="alertBtn leftBtn" onclick="closeWarning()">بسیار خب</button>
            </div>
        </div>
    </div>

</div>


<div id="successNotifiAlert" class="notifAlert">
    پست شما با موفقیت ثبت شد
</div>

<script>
    function showSuccessNotifi(_msg, _side = 'right', _color = '#0076ac'){
        document.getElementById('successNotifiAlert').innerText = _msg;
        $('#successNotifiAlert').addClass('topAlert');

        if(_side == 'right')
            $('#successNotifiAlert').addClass('rightAlert');
        else
            $('#successNotifiAlert').addClass('leftAlert');


        if(_color == 'red')
            $('#successNotifiAlert').addClass('redAlert')
        else if(_color == 'green')
            $('#successNotifiAlert').addClass('greenAlert')

        setTimeout(function(){
            $('#successNotifiAlert').removeClass('topAlert');

            setTimeout(function () {
                $('#successNotifiAlert').removeClass('greenAlert');
                $('#successNotifiAlert').removeClass('redAlert');
                $('#successNotifiAlert').removeClass('leftAlert');
                $('#successNotifiAlert').removeClass('rightAlert');
            }, 1000);

        }, 5000);
    }


    function openWarning(_text){
        $('#warningBody').html(_text);
        $('#warningBoxDiv').css('display', 'flex');
    }

    function closeWarning(){
        $('#warningBoxDiv').css('display', 'none');
    }

    function openErrorAlert(_text){
        $('#alertBodyDiv').html(_text);
        $('#alertBoxDiv').css('display', 'flex');
    }
    function closeErrorAlert(){
        $('#alertBoxDiv').css('display', 'none');
    }
</script>
