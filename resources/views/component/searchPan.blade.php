<link rel="stylesheet" href="{{URL::asset('css/component/searchPan.css')}}">

<style>
    .placeHolderAnime{
        background: radial-gradient(circle, #6b6b6b 0%, rgba(58,58,58,1) 35%);
        background-size: 400% 400%;
        animation: gradient 1.5s infinite;
    }
    .resultLine{
        width: 60%;
        height: 10px;
        margin-bottom: 10px;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }
        100% {
            background-position: 100% 50%;
        }
    }
</style>

<span id="searchPane" class="hidden searchPanes">
        <div id="searchDivForScroll" class="prw_rup prw_search_typeahead spSearchDivForScroll">
            <div>
                <div class="typeahead_align">
                    <div id="firstPanSearchText" class="spGoWhere"></div>
                    <input onkeyup="searchMain(event, this.value)" type="text" id="searchPanInput" class="typeahead_input searchPaneInput" placeholder="دنبال چه محتوایی هستید؟"/>
                </div>

                <div class="spBorderBottom"></div>
                <div class="mainContainerSearch">
                    <div class="data_holder searchPangResultSection display-none">
                        <div id="searchPangResult"></div>
                        <div id="placeHolderResult" style="display: none;">
                            <div style="margin-bottom: 40px">
                                <div class="resultLine placeHolderAnime"></div>
                                <div class="resultLine placeHolderAnime" style="width: 30%"></div>
                            </div>
                            <div>
                                <div class="resultLine placeHolderAnime"></div>
                                <div class="resultLine placeHolderAnime" style="width: 30%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="visitSuggestionDiv">
                            <div class="visitSuggestionText">بازدید های اخیر شما</div>

                            <div id="recentlyRowMainSearch" class="visitSuggestion4Box">
                                <div class="prw_rup prw_shelves_rebrand_poi_shelf_item_widget spBoxOfSuggestion">
                                    <div class="mainSearchpoi">
                                        <div class="prw_rup prw_common_thumbnail_no_style_responsive prw_common_thumbnail_no_style_responsive22">
                                            <div class="prv_thumb has_image" style="height: 100%">
                                                <div class="image_wrapper spImageWrapper landscape landscapeWide mainSearchImgTop">
                                                    <img src="##pic##" alt="##title##" class="image" style="height: 100%">
                                                </div>
                                            </div>
                                        </div>
                                        <a href="##redirect##" class="textsOfRecently">
                                            <div class="detail direction-rtl" style="width: 100%;">
                                                <div class="textsOfRecently_text">##title##</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                </div>

            </div>
        </div>
        <div onclick="$('#searchPane').addClass('hidden'); $('.dark').hide()" class="closeIcon searchPaneCloseIcon"></div>
    </span>

<script>
    let searchRequestNumber = 0;

    function openMainSearch(){
        showLastPages();

        $('#searchPane').removeClass('hidden');
        $('#darkModeMainPage').toggle();
        $('#searchPanInput').val('');
        $('#searchPanInput').focus();

        $("#searchPangResult").empty();
    };

    function searchMain(e, val = '') {
        if (val.trim().length < 2) {
            val = $("#searchPanInput").val();
            $('.searchPangResultSection').addClass('display-none');
        }
        else {
            searchRequestNumber++;
            $('.searchPangResultSection').removeClass('display-none');
            $('#placeHolderResult').show();
            $('#searchPangResult').hide();

            $.ajax({
                type: "post",
                url: '{{route("video.search")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    value: val,
                    num: searchRequestNumber
                },
                success: function (response) {
                    try {
                        response = JSON.parse(response);
                        if(response.status == 'ok' && response.num == searchRequestNumber)
                            createSearchResponse(response.result);
                    }
                    catch (e) {
                    }
                },
                error: function(err){
                    console.log(err)
                }
            });
        }
    }

    function createSearchResponse(response){
        newElement = "";

        if ($("#searchPanInput").val().trim().length == 0) {
            $('.searchPangResultSection').addClass('display-none');
            return;
        }

        for (i = 0; i < response.length; i++) {
            newElement += '<a class="aSearchResult" href="' + response[i].url + '">\n';
            newElement += "<div class='searchPanSuggest searchPanSuggestName' id='suggest_" + i + "'>" + response[i].title + "</div>";
            newElement += "<div class='searchPanSuggest searchPanSuggestCategory' id='suggest_" + i + "'>" + response[i].category + "</div></a>";
        }

        if (response.length != 0)
            $('.searchPangResultSection').removeClass('display-none');
        else
            $('.searchPangResultSection').addClass('display-none');
        $("#searchPangResult").empty().append(newElement);


        $('#placeHolderResult').hide();
        $('#searchPangResult').show();

    }


    let recentlyMainSearchSample = 0;
    let localStorageData = 0;
    @if(isset($localStorageData))
        localStorageData = {!! json_encode($localStorageData) !!}
    @endif

    if (typeof(Storage) !== "undefined") {
        var lastPages;

        lastPages = localStorage.getItem('lastPagesKoochitaTv');
        lastPages = JSON.parse(lastPages);

        if(localStorageData != 0){
            if(lastPages != null) {
                for(i = 0; i < lastPages.length; i++){
                    if(lastPages[i]['redirect'] == localStorageData['redirect']){
                        lastPages.splice(i, 1);
                    }
                }
                lastPages.unshift(localStorageData);
                if (lastPages.length == 9)
                    lastPages.pop();
            }
            else {
                lastPages = [];
                lastPages.unshift(localStorageData);
            }

            localStorage.setItem('lastPagesKoochitaTv', JSON.stringify(lastPages));
        }

    }
    else
        console.log('your browser not support localStorage');

    function showLastPages(){

        var lastPages = localStorage.getItem('lastPagesKoochitaTv');
        lastPages = JSON.parse(lastPages);

        if(recentlyMainSearchSample == 0)
            recentlyMainSearchSample = $('#recentlyRowMainSearch').html();

        $('#recentlyRowMainSearch').html('');

        if(lastPages != null){

            for(i = 0; i < lastPages.length; i++){
                let text = recentlyMainSearchSample;
                let fk = Object.keys(lastPages[i]);

                for (let x of fk) {
                    let t = '##' + x + '##';
                    let re = new RegExp(t, "g");
                    text = text.replace(re, lastPages[i][x]);
                }

                $('#recentlyRowMainSearch').append(text);
            }
        }
    }
</script>
