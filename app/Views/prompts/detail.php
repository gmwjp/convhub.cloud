<?=$this->element("page_title")?>
<div class="form-group">
    <div class="item-title">
        プロンプト名
    </div>
    <div>
        <?=esc($prompt->name)?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        本文
    </div>
    <div>
    <?=nl2br(esc($prompt->body))?>
    </div>
</div>
<div class="text-center">
    <div class="mt-2">
        <button type="button" data-action="/prompts/edit/<?=esc($prompt->id)?>" class="btn btn-outline-secondary href">編集する</button>
        <button type="button" data-confirm="このデータを削除してよろしいですか？" data-action="/prompts/del/<?=esc($prompt->id)?>" class="btn btn-outline-danger href">削除する</button>
    </div>
    <div class="mt-2">
        <a href="/prompts/index" class="btn btn-link">一覧に戻る</a>
    </div>
</div>