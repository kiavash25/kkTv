<div class="loaderDiv" id="fullPageLoader" style="display: none">
    <div class="loader_200">
        <img src="{{URL::asset('images/mainPics/loading.gif')}}" style="width: 300px;">
    </div>
</div>

<script>
    function openLoading(){
        $('#fullPageLoader').css('display', 'flex');
    }
    function closeLoading(){
        $('#fullPageLoader').css('display', 'none');
    }
</script>
