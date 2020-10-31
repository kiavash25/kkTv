<style>
    .playListObj{
        width: 210px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .playListObj .pic{
        width: 200px;
        height: 130px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }
    .playListObj .pic .countInfo{
        position: absolute;
        right: 0;
        top: 0;
        background: #000000a8;
        height: 100%;
        width: 70px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
    }
    .playListObj .pic .hoverPlay{
        position: absolute;
        top: 0;
        right: 0;
        background: #000000cc;
        width: 100%;
        height: 100%;
        display: none;
        justify-content: center;
        align-items: center;
        color: white;
        font-weight: bold;
    }
    .playListObj .pic:hover .hoverPlay{
        display: flex;
    }
    .playListObj .name{
        text-align: right;
        width: 100%;
        padding: 0px 10px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 10px 0px;
        color: white;
    }
</style>

<div id="playListSample" style="display: none;">
    <a href="##url##" class="playListObj">
        <div class="pic">
            <img src="##thumbnail##" alt="">
            <div class="countInfo">
                ##videoCount##
                ویدیو
            </div>
            <div class="hoverPlay">
                پخش همه
            </div>
        </div>
        <div class="name">
            ##name##
        </div>
    </a>
</div>

<script>
    var playListObjSample = $('#playListSample').html();
    $('#playListSample').remove();

    function createPlayListObjGroups(_group){
        let text = '';
        _group.map(item => text += createPlayListObjSingle(item));
        return text;
    }

    function createPlayListObjSingle(_item){
        let text = playListObjSample;
        let fk = Object.keys(_item);
        for (let x of fk)
            text = text.replace(new RegExp('##' + x + '##', "g"), _item[x]);

        return text;
    }
</script>
