<?=$this->element("page_title")?>
<div class="form-group">
    <div class="item-title">
        フォーム名
    </div>
    <div>
        <?=esc($form->name)?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        本文
    </div>
    <div>
    <?=nl2br(esc($form->body))?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        設問項目
    </div>
    <div>
        <?if($form_items){?>
            <table class="table">
                <tr>
                    <th>項目名</th>
                    <th>区分</th>
                    <th>必須</th>
                </tr>
                <?foreach($form_items as $item){?>
                    <tr>
                        
                        <td><?=esc($item->name)?></td>
                        <td><?=esc($this->model("FormItems")->params["section"][$item->section])?></td>
                        <td><?=esc($this->model("FormItems")->params["required"][$item->required])?></td>
                    </tr>
                <?}?>
            </table>
        <?}?>
    </div>
</div>
<div class="text-center">
    <div class="mt-2">
        <button type="button" data-action="/forms/edit/<?=esc($form->id)?>" class="btn btn-outline-secondary href">編集する</button>
        <button type="button" data-confirm="このデータを削除してよろしいですか？" data-action="/forms/del/<?=esc($form->id)?>" class="btn btn-outline-danger href">削除する</button>
    </div>
    <div class="mt-2">
        <a href="/forms/index" class="btn btn-link">一覧に戻る</a>
    </div>
</div>
<?=$this->element("page_title",["title"=>"サブフォーム"])?>
<div class="text-right">
    <a href="/subforms/add/<?=esc($form->id)?>" class="btn btn-dark">新規追加</a>
</div>
<?if($subforms){?>
    <?foreach($subforms as $subform){?>
    <a class="list-group-item list-group-item-action" href="/subforms/detail/<?=esc($subform->id)?>">
        <?=esc($subform->name)?>
    </a>
    <?}?>
<?}?>