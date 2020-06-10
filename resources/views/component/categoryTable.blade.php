<link rel="stylesheet" href="{{URL::asset('css/layout/vodCategoryTable.css')}}">

<div class="categoryBackBody">
    <div class="categoryBody">
        <div class="row" style="height: 100%; padding: 10px; padding-right: 0px; position: relative; display: flex;">

            <div class="closeIcon closeCategoryBodyDiv" onclick="closeCategoryMenu()">
                بستن
            </div>

            <div class="categoryBodySection categoryBodySectionLeft">
                <div class="categoryMainHeader">
                    دسته بندی های موجود
                </div>

                <div class="categoryMainCat">
                    @foreach($vodCategory as $mainCat)
                        <div class="categoryTabs" onclick="openSubCategoryMenuTab({{$mainCat->id}}, this)">
                            <div class="categoryTabName">
                                {{$mainCat->name}}
                            </div>
                            <div class="categoryArrowDiv">
                                <div class="categoryLeftArrow"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="categoryBodySection categoryBodySectionRight showCategoryBodySectionRight">
                <div class="categoryMainHeader" style="padding-right: 0;">
                    انتخاب کنید
                    <span id="categoryHeaderName" class="categoryHeaderName"></span>
                </div>
                <div class="closeCategoryBodyDiv backCategory" onclick="backCategoryMenu()">
                    <div class="backArrow"></div>
                    بازگشت
                </div>

                @foreach($vodCategory as $mainCat)
                    <div id="subCategoryMenu_{{$mainCat->id}}" class="subCategoryBody">
                        @foreach($mainCat->sub as $item)
                            <a href="{{route('video.list', ['kind' => 'category', 'value' => $item->id])}}" class="subCategoryDiv">
                                <img src="{{$item->offIcon}}" class="categoryIcon offIcon">
                                <img src="{{$item->onIcon}}" class="categoryIcon onIcon">
                                <div class="subCategoryName">
                                    {{$item->name}}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

<script>

    function openCategoryMenu(){
        $('.categoryBodySectionRight').addClass('showCategoryBodySectionRight');
        $('.categoryBodySectionLeft').removeClass('showCategoryBodySectionRight');
        $('.categoryBackBody').css('display', 'flex');
    }

    function backCategoryMenu(){
        $('.categoryBodySectionRight').addClass('showCategoryBodySectionRight');
        $('.categoryBodySectionLeft').removeClass('showCategoryBodySectionRight');
    }

    function closeCategoryMenu(){
        $('.categoryBackBody').css('display', 'none');
    }

    function openSubCategoryMenuTab(_id, _element){
        $('.categoryBodySectionRight').removeClass('showCategoryBodySectionRight');
        $('.categoryBodySectionLeft').addClass('showCategoryBodySectionRight');
        $('.subCategoryBody').css('display', 'none');
        $('#subCategoryMenu_' + _id).css('display', 'flex');
        $('.categoryTabsActive').removeClass('categoryTabsActive');
        $(_element).addClass('categoryTabsActive');

        let name = $($(_element).children()[0]).text();
        $('#categoryHeaderName').text(name)
    }

    $(window).keydown(function (e) {
        if(e.keyCode == 27 && $('.categoryBackBody').css('display') != 'none')
            closeCategoryMenu();
    })

</script>
