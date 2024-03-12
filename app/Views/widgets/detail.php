<?=$this->element("page_title")?>
<div class="form-group">
    <div class="item-title">
        ウィジェット名
    </div>
    <div>
        <?=esc($widget->name)?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        ウィジェットURL
    </div>
    <div>
        <div class="input-group">
            <input type="text" class="form-control" readonly="true" value="<?=$_SERVER["SITE_URL"]?>/widgets/show/<?=esc($widget->code)?>">
            <button class="btn btn-light" type="button" id="copy_button"><span class="fal fa-copy"></span></button>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
    問い合わせフォームへのリンク

    </div>
    <div>
        <?if($form){?><?=esc($form->name)?><?}?>
    </div>
</div>
<div class="text-center">
    <div class="mt-2">
        <button type="button" data-action="/widgets/edit/<?=esc($section)?>/<?=esc($widget->id)?>" class="btn btn-outline-secondary href">編集する</button>
        <button type="button" data-confirm="このデータを削除してよろしいですか？" data-action="/widgets/del/<?=esc($widget->id)?>" class="btn btn-outline-danger href">削除する</button>
    </div>
    <div class="mt-2">
        <a href="/widgets/index/<?=esc($widget->section)?>" class="btn btn-link">一覧に戻る</a>
    </div>
</div>
<script>
$(document).ready(function(){
    $('#copy_button').click(function(){
        var copyText = $(this).closest('.input-group').find('input').val();
        var temp = $("<input>");
        $('body').append(temp);
        temp.val(copyText).select();
        document.execCommand("copy");
        temp.remove();
    });
});
</script>