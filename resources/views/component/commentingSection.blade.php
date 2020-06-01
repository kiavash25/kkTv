<style>
    .successLabel{
        background: green !important;
        font-size: 13px ;
        margin-right: 10px;
    }
    .LikeIcon:before{
        content: '\B' !important;
        font-family: Shazde_Regular2 !important;
    }
    .mainUseruserNameComment{
        font-size: 22px;
        color: #0076a3;
    }
    .commentSectionBody{
        background: white;
        width: 100%;
        border-radius: 10px;
        padding: 15px;
        background-color: #232323;
        color: white;
    }

    .commentInputSection{
        display: flex;
        align-items: flex-start;
    }

    .commentAnsesSection{
        width: 100%;
        border-bottom: 1px solid red;
    }

    .commentInput{
        width: 88%;
        margin-right: 11px;
        padding: 10px;
        border-radius: 5px;
        background: #7d7d7d;
        resize: none;
        border: none;
    }
    .commentInput::placeholder{
        color: white;
    }
    .commentInput::-ms-input-placeholder{
        color: white;
    }
    .commentInput::-ms-input-placeholder{
        color: white;
    }
    .commentAnsTextSection{
        width: 98%;
        margin-right: 11px;
        padding: 10px;
        border-radius: 5px;
        background: #545454;
        border: none;
        font-size: 14px;
    }
    .topOfcommentAnsTextSection{
        width: 87%;
    }
    .topWho{
        display: flex;
        align-items: center;
    }
    .whoAns{
        font-size: 20px;
        color: #0076a3;
        font-weight: bold;
        display: flex;
        align-items: center;
        margin-left: 5px;
    }
    .whoAnsTo{
        font-weight: bold;
        color: darkgray;
    }
    .commentAnsToAns{
        margin-right: 12px;
    }
    .commentAnsInfos{
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        margin: 5px;
    }
    .commentAnsRight{
        display: flex;
        font-size: 27px;
        margin-right: 10px;
    }
    .commentAnsLeft{
        color: #fcc156;
        cursor: pointer;
    }
    .commentAnsLikeSection{
        display: flex;
        justify-content: space-around;
        font-size: 30px;
        line-height: 25px;
    }
    .likeAnsIconDiv{
        width: 26px;
        height: 26px;
        position: relative;
        cursor: pointer;
    }
    .disLikeAnsIconDiv{
        width: 26px;
        height: 26px;
        position: relative;
        cursor: pointer;
    }
    .LikeIconEmptySett:before, .DisLikeIconEmptySett:before{
        position: absolute;
        left: 0px;
    }


    .disLikeAnsIconDiv:hover .DisLikeIconEmptySett:before{
        content: '\E058';
        color: darkred;
    }
    .likeAnsIconDiv:hover .LikeIconEmptySett:before{
        content: '\E057';
        color: red;
    }
    .redLikeIcon:before{
        content: '\E057' !important;
        font-family: Shazde_Regular2 !important;
        color: red;
    }
    .redDisLikeIcon:before{
        content: '\E058' !important;
        font-family: Shazde_Regular2 !important;
        color: darkred;
    }


    .commentInputSendButton{
        background: #0076a3;
        color: white;
        padding: 10px;
        border-radius: 10px;
        margin-right: 10px;
        cursor: pointer;
    }

    .acceptedComment{
        margin-top: 25px;
    }

    .acceptedCommentText{
        white-space: pre-line;
        text-align: justify;
        padding: 0px 25px;
        color: #bfbfbf;
    }
    .acceptedCommentSett{
        padding: 4px 15px;
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        border-top: solid 1px red;
        border-bottom: solid 1px red;
    }
    .acceptedCommentRight{
        display: flex;
        align-items: center;
        font-size: 12px;
    }
    .acceptedCommentLeft{
        display: flex;
    }
    .acceptedCommentAnsButton{
        /* color: black; */
        /* background-color: #fcc156; */
        padding: 2px 8px;
        background-color: #0076a3;
        border-radius: 6px;
        cursor: pointer;
        color: white;
        text-align: center;
        width: 89px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        margin-right: 10px;
    }
    .mainAnsOf{
        display: flex;
        width: 85%;
        margin-right: auto;
        margin-left: auto;
    }
    .ansOf{
        display: none;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .topOfcommentAnsTextSection{
            width: 100% ;
        }
        .topWho{
            align-items: flex-start;
        }
    }
    @media (max-width: 600px) {
        .commentSectionBody{
            padding: 5px;
        }
        .commentAnsTextSection{
            margin-right: 0px;
        }
        .mainAnsOf{
            width: 90%;
        }
    }
</style>


<div id="commentSectionBody" class="commentSectionBody">
    <div class="commentInputSection">
        <div class="userPicDiv">
            <img id="userPictureCommenting" src="" alt="koochita">
        </div>
        <textarea class="commentInput" name="AnsToComment_0" id="AnsToComment_0" placeholder="شما چه نظری دارید؟" rows="1" maxlength="255" onclick="checkLoginForCommenting(this)"></textarea>
        <div class="commentInputSendButton" onclick="sendCommentTo(0)">ارسال</div>
    </div>
    <hr style="border-color:darkgray; margin: 10px 0px">

    <div id="commentsDiv" class="commentSectionBody"></div>

    <div id="mainCommentSample" style="display: none">
        <div id="comment_##id##" class="commentAnsesSection">

            <div class="commentInputSection" style="margin-top: 10px;">
                <div class="userPicDiv userPicAnsToReviewPc">
                    <img src="##userPic##" alt="##username##">
                </div>
                <div class="topOfcommentAnsTextSection">

                    <div style="display: flex; align-items: center">
                        <div class="commentAnsTextSection">
                            <div style="display: flex">
                                <div class="userPicDiv userPicAnsToReviewMobile">
                                    <img src="##userPic##" alt="##username##">
                                </div>
                                <div class="topWho">
                                    <div class="whoAns">
                                        ##username##
                                        <span class="label label-success successLabel" style="display: ##newLabel##">در انتظار تایید</span>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: justify; white-space: pre-line">##text##</div>
                            <div class="commentAnsToAns hideOnPc" style="justify-content: flex-end;">
                                <div class="acceptedCommentAnsButton" onclick="openAnsToComment(##id##)">
                                    پاسخ دهید
                                </div>
                            </div>
                        </div>
                        <div class="acceptedCommentAnsButton hideOnPhone" onclick="openAnsToComment(##id##)">
                            پاسخ دهید
                        </div>
                    </div>

                    <div id="ansTo_##id##" class="commentInputSection" style="margin-top: 10px; display: none;">
                        <div class="userPicDiv ">
                            <img src="##authPicture##">
                        </div>
                        <textarea class="commentInput" name="AnsToComment_##id##" id="AnsToComment_##id##" placeholder="شما چه پاسخی به ##username## دارید؟" rows="1"></textarea>
                        <div class="commentInputSendButton" onclick="sendCommentTo(##id##)">ارسال</div>
                    </div>

                    <div class="commentAnsInfos">
                        <div class="commentAnsRight">
                            <div style="color: #0076a3; display: flex;" onclick="commentFeedBack(##id##, 1)">
                                <span id="likeCount_##id##" style="color: white;">##like##</span>
                                <span class="LikeIcon" style="font-size: 30px;"></span>
                            </div>
                            <div style="color: #0076a3; display: flex; margin-right: 10px;" onclick="commentFeedBack(##id##, -1)">
                                <span id="disLikeCount_##id##" style="color: white">##disLike##</span>
                                <span class="DisLikeIcon" style="font-size: 30px"></span>
                            </div>
                            <div style="color: #0076a3; display: flex; margin-right: 10px;">
                                <span id="ansCountComment_##id##" style="color: white">##ansCount##</span>
                                <span class="CommentIcon"></span>
                            </div>
                        </div>
                        <div id="showAnsButton_##id##"  class="commentAnsLeft" onclick="openAnsOnReview(##id##)" style="display: none">
                            مشاهده پاسخ ها
                        </div>
                    </div>

                </div>
            </div>

            <div id="ansOf_##id##" class="ansOf mainAnsOf"></div>
        </div>
    </div>

    <div id="ansCommentSample" style="display: none;">
        <div id="comment_##id##" class="commentAnsesSection" style="border: none">

            <div class="commentInputSection" style="margin-top: 10px;">
                <div class="userPicDiv userPicAnsToReviewPc">
                    <img src="##userPic##" alt="##username##">
                </div>
                <div class="topOfcommentAnsTextSection">
                    <div style="display: flex; align-items: center">
                        <div class="commentAnsTextSection">
                            <div style="display: flex">
                                <div class="userPicDiv userPicAnsToReviewMobile">
                                    <img src="##userPic##" alt="##username##">
                                </div>
                                <div class="topWho">
                                    <div class="whoAns">
                                        ##username##
                                        <span class="label label-success successLabel" style="display: ##newLabel##">در انتظار تایید</span>
                                    </div>
                                    <div class="whoAnsTo">
                                        در پاسخ به ##ansToUsername##
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: justify; white-space: pre-line">##text##</div>
                            <div class="commentAnsToAns hideOnPc" style="justify-content: flex-end;">
                                <div class="acceptedCommentAnsButton" onclick="openAnsToComment(##id##)">
                                    پاسخ دهید
                                </div>
                            </div>
                        </div>

                        <div class="acceptedCommentAnsButton hideOnPhone" onclick="openAnsToComment(##id##)">
                            پاسخ دهید
                        </div>
                    </div>

                    <div id="ansTo_##id##" class="commentInputSection" style="margin-top: 10px; display: none;">
                        <div class="userPicDiv ">
                            <img src="##authPicture##">
                        </div>
                        <textarea class="commentInput" name="AnsToComment_##id##" id="AnsToComment_##id##" placeholder="شما چه نظری دارید؟" rows="1"></textarea>
                        <div class="commentInputSendButton" onclick="sendCommentTo(##id##)">ارسال</div>
                    </div>

                    <div class="commentAnsInfos">
                        <div class="commentAnsRight">
                            <div style="color: #0076a3; display: flex" onclick="commentFeedBack(##id##, 1)">
                                <span id="likeCount_##id##" style="color: white">##like##</span>
                                <span class="LikeIcon" style="font-size: 30px"></span>
                            </div>
                            <div style="color: #0076a3; display: flex; margin-right: 10px;" onclick="commentFeedBack(##id##, -1)">
                                <span id="disLikeCount_##id##" style="color: white">##disLike##</span>
                                <span class="DisLikeIcon" style="font-size: 30px"></span>
                            </div>
                            <div style="color: #0076a3; display: flex; margin-right: 10px;">
                                <span id="ansCountComment_##id##" style="color: white">##ansCount##</span>
                                <span class="CommentIcon"></span>
                            </div>
                        </div>
                        <div id="showAnsButton_##id##" class="commentAnsLeft" onclick="openAnsOnReview(##id##)" style="display: none">
                            مشاهده پاسخ ها
                        </div>
                    </div>

                </div>

            </div>

            <div id="ansOf_##id##" class="ansOf"></div>
        </div>
    </div>
</div>

<script>
    let userPicture = '{{$userPicture}}';
    let commentingStoreUrl = null;
    let commentingDefaultData = null;
    let mainCommentSample = null;
    let ansCommentSample = null;

    mainCommentSample = $('#mainCommentSample').html();
    $('#mainCommentSample').remove();
    ansCommentSample = $('#ansCommentSample').html();
    $('#ansCommentSample').remove();

    $('#userPictureCommenting').attr('src', userPicture);

    function checkLoginForCommenting(_element){
        if (!hasLogin) {
            showLoginPrompt();
            $(_element).prop('readonly', true);
            return;
        }
    }

    function initCommentingSection(_defaultData){
        commentingDefaultData = JSON.stringify(_defaultData);
    }

    function sendCommentTo(_kind){
        if (!hasLogin) {
            showLoginPrompt();
            return;
        }

        let text = $('#AnsToComment_' + _kind).val();
        if(text.trim().length > 0){
            $.ajax({
                type: 'post',
                url: '{{route('streaming.setVideoComment')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    data: commentingDefaultData,
                    text: text,
                    ansTo: _kind
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            $('#AnsToComment_' + _kind).val('');
                            let ansCount = parseInt($('#ansCountComment_' + _kind).text());
                            $('#ansCountComment_' + _kind).text(ansCount+1);

                            fillMainCommentSection([response.comment], _kind);
                        }
                    }
                    catch (e) {
                        console.log(e);
                    }
                },
                error: function(err){

                }
            })
        }
    }

    function fillMainCommentSection(_comment, _id = 0){
        // _comment = [
            // {
            //   id,
            //   text,
            //   username,
            //   userPic,
            //   time,
            //   text,
            //   ansCount,
            //   like,
            //   disLike,
            //   ansToUsername,
            //   comments: _comment,
            // }
        // ];
        for(let i = 0; i < _comment.length; i++){
            let text;
            if(_id == 0)
                text = mainCommentSample;
            else
                text = ansCommentSample;

            let fk = Object.keys(_comment[i]);
            for (let x of fk) {
                t = '##' + x + '##';
                re = new RegExp(t, "g");
                text = text.replace(re, _comment[i][x]);
            }
            t = '##authPicture##';
            re = new RegExp(t, "g");
            text = text.replace(re, userPicture);

            if(_comment[i]['confirm'] == 1)
                showNewLabel = 'none';
            else
                showNewLabel = 'block';

            t = '##newLabel##';
            re = new RegExp(t, "g");
            text = text.replace(re, showNewLabel);

            if(_id == 0)
                $('#commentsDiv').prepend(text);
            else {
                $('#showAnsButton_' + _id).show();
                $('#ansOf_' + _id).prepend(text);
            }

            fillMainCommentSection(_comment[i]['comments'], _comment[i]['id']);
        }
    }

    function openAnsToComment(_id){
        if (!hasLogin) {
            showLoginPrompt();
            return;
        }

        $('#ansTo_' + _id).toggle();
    }

    function openAnsOnReview(_id){
        if($('#ansOf_' + _id).css('display') == 'flex')
            $('#ansOf_' + _id).css('display', 'none');
        else
            $('#ansOf_' + _id).css('display', 'flex');
    }

    function commentFeedBack(_id, _like){
        if (!hasLogin) {
            showLoginPrompt();
            return;
        }

        $.ajax({
            type: 'post',
            url: '{{route("streaming.setVideoFeedback")}}',
            data: {
                _token: '{{csrf_token()}}',
                commentId: _id,
                kind: 'likeComment',
                videoId: video.id,
                like: _like
            },
            success: function(response){
                try{
                    response = JSON.parse(response);
                    if(response['status'] == 'ok'){
                        $('#likeCount_' + _id).text(response['like']);
                        $('#disLikeCount_' + _id).text(response['disLike']);
                    }
                }
                catch (e) {
                    console.log(e)
                }
            },
            error: function(err){

            }
        })
    }




</script>