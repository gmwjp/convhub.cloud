<?=$this->element("page_title")?>
<div class="form-group">
    <div class="item-title">
        グループ名
    </div>
    <div>
        <?=esc($group->name)?>
    </div>
</div>
<div class="text-center">
    <div class="mt-2">
        <button type="button" data-action="/groups/edit/<?=esc($group->id)?>" class="btn btn-outline-secondary href">編集する</button>
        <button type="button" data-confirm="このデータを削除してよろしいですか？" data-action="/groups/del/<?=esc($group->id)?>" class="btn btn-outline-danger href">削除する</button>
    </div>
    <div class="mt-2">
        <a href="/groups/index" class="btn btn-link">一覧に戻る</a>
    </div>
</div>