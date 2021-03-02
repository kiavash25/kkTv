<style>
    .askQuestionSection{
        width: 100%;
    }
    .askQuestionSection input{
        width: 100%;
        margin-top: 10px;
        border-radius: 5px;
        border: none;
        padding: 5px;
    }
    .askQuestionSection button{
        width: 100px;
        margin-right: auto;
        border-radius: 5px;
        padding: 5px;
        margin-top: 10px;
        background: var(--koochita-blue);
        border: none;
        box-shadow: 2px 2px 3px 1px black;
    }
</style>
<div class="descriptionSection askQuestionSection">
    <div class="headerWithLine">
        <div class="headerWithLineText"> پرسش </div>
    </div>
    <div class="descriptionSectionBody">
        <div>شما در این قسمت می توانید سوال خود را مطرح کنید تا از استاد مربوطه پرسیده شود.</div>
        <div style="display: flex; flex-direction: column;">
            <input type="text" id="questionInput" placeholder="سوال خود را اینجا بنویسید...">
            <button onclick="storeQuestion()">ارسال</button>
        </div>
    </div>
</div>
<script>
    function storeQuestion(){
        var value = $('#questionInput').val();
        if(value.trim().length != 0){
            openLoading();
            var data = {
                videoId: '{{$video->id}}',
            };

            $.ajax({
                type: 'post',
                url: '{{route("video.setVideoComment")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    data: JSON.stringify(data),
                    isLive: 1,
                    text: value,
                    ansTo: 0,
                },
                complete: closeLoading,
                success: response => {
                    response = JSON.parse(response);
                    if(response.status == 'ok'){
                        showSuccessNotifi('سوال شما با موفقیت ثبت شد', 'left', 'var(--koochita-blue)');
                        $('#questionInput').val('');
                    }
                    else
                        showSuccessNotifi('در ثبت سوال مشکلی پیش امده دوباره تلاش کنید', 'left', 'red');
                },
                error: () => showSuccessNotifi('در ثبت سوال مشکلی پیش امده دوباره تلاش کنید', 'left', 'red')
            })
        }
    }
</script>
