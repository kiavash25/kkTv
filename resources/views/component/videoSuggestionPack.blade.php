<link rel="stylesheet" href="{{URL::asset('css/component/suggestionPack.css')}}">

<div id="videoSuggestionSample" style="display: none">
    <div id="##id##" class="swiper-slide videoSuggestion">
        <div class="videoSuggMainContent" style="display: none; width: 100%;">
            <a href="##url##" class="videoSugPicSection">
                <img src="##pic##" class="resizeImgClass videoSugPic" onload="showThisVideoSugg(this)">
                <div class="overPicSug likeOverPic">
                    <div style="display: flex; margin-left: 5px;">
                        <span class="likeOverPicNum">##disLike##</span>
                        <span class="DisLikeIcon likeOverPicIcon"></span>
                    </div>
                    <div style="display: flex;">
                        <span class="likeOverPicNum">##like##</span>
                        <span class="LikeIcon likeOverPicIcon"></span>
                    </div>
                </div>
                <div class="overPicSug seenOverPic">
                    <span>##seen##</span>
                    <img src="{{URL::asset('images/mainPics/eye.png')}}" class="eyeClass">
                </div>
                <div class="playIconDiv">
                    <img src="{{URL::asset('images/mainPics/play.png')}}" class="playIconDivIcon">
                </div>
            </a>
            <div class="videoSugInfo">
                <div class="videoSugUserInfo">
                    <a href="##url##" class="videoSugName">
                        ##title##
                    </a>
                </div>

                <div class="videoSugUserPic">
                    <div class="videoSugUserPicDiv">
                        <img src="##userPic##" alt="koochita" style="width: 100%; height: 100%;">
                    </div>
                    <div class="videoUserInfoName">
                        <div class="videoSugUserName">
                            ##username##
                        </div>
                        <div class="videoSugTime">
                            ##time##
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="videoSuggPlaceHolderDiv" style=" width: 100%;">
            <div class="videoSugPicSection placeHolderAnime"></div>
            <div class="videoSugInfo">
                <div class="videoSugUserInfo videoSugUserInfoPlaceHolder">
                    <div class="videoSugName videoSuggNamePlaceHolder placeHolderAnime" style="height: 15px"></div>
                </div>

                <div class="videoSugUserPic">
                    <div class="videoSugUserPicDiv placeHolderAnime"></div>
                    <div class="videoUserInfoName">
                        <div class="videoSugUserName videoSuggNamePlaceHolder placeHolderAnime" style="margin-bottom: 5px"></div>
                        <div class="videoSugTime videoSuggNamePlaceHolder placeHolderAnime"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    let videoSample = $('#videoSuggestionSample').html();
    $('#videoSuggestionSample').remove();

    function showThisVideoSugg(_element){
        $(_element).parent().parent().css('display', 'block');
        $(_element).parent().parent().next().remove();
        resizeThisImg(_element);
    }

    function resizeThisImg(_element){
        var img = $(_element);
        var imgW = img.width();
        var imgH = img.height();

        var secW = img.parent().width();
        var secH = img.parent().height();

        if(imgH < secH){
            img.css('height', '100%');
            img.css('width', 'auto');
        }
        else if(imgW < secW){
            img.css('width', '100%');
            img.css('height', 'auto');
        }

    }

    function createVideoSuggestionDiv(_pack, _destId, _callBack = '', _append = false){
        // _pack = {
        //     id : ,
        //     title: ,
        //     url : ,
        //     pic : ,
        //     like : ,
        //     disLike: ,
        //     seen : ,
        //     userPic : ,
        //     username : ,
        //     time : ,
        // }

        if(!_append)
            $('#' + _destId).html('');

        for(let i = 0; i < _pack.length; i++){
            let text = videoSample;
            let fk = Object.keys(_pack[i]);
            for (let x of fk) {
                t = '##' + x + '##';
                re = new RegExp(t, "g");
                text = text.replace(re, _pack[i][x]);
            }
            $('#' + _destId).append(text);
        }

        if(typeof _callBack === 'function')
            _callBack();
    }
</script>
