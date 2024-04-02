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
            <button class="btn btn-light copy_button" data-value="<?=$_SERVER["SITE_URL"]?>/widgets/show/<?=esc($widget->code)?>" type="button"><span class="fal fa-copy"></span></button>
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
        <button type="button" data-confirm="このデータを削除してよろしいですか？" data-action="/widgets/del/<?=esc($widget->id)?>" class="btn btn-outline-danger href">削除する</button>
    </div>
    <div class="mt-2">
        <a href="/forms/widgets/<?=esc($widget->form_id)?>" class="btn btn-link">一覧に戻る</a>
    </div>
</div>