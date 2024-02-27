<?=$this->element("page_title")?>
<div class="form-group">
    <div class="item-title">
        テンプレート名
    </div>
    <div>
        <?=esc($template->name)?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        本文
    </div>
    <div>
    <?=nl2br(esc($template->body))?>
    </div>
</div>
<div class="text-center">
    <div class="mt-2">
        <button type="button" data-action="/templates/edit/<?=esc($template->id)?>" class="btn btn-outline-secondary href">編集する</button>
        <button type="button" data-confirm="このデータを削除してよろしいですか？" data-action="/templates/del/<?=esc($template->id)?>" class="btn btn-outline-danger href">削除する</button>
    </div>
    <div class="mt-2">
        <a href="/templates/index" class="btn btn-link">一覧に戻る</a>
    </div>
</div>