<?=$this->element("page_title")?>
<div class="form-group">
    <div class="item-title">
        サブフォーム名
    </div>
    <div>
        <?=esc($subform->name)?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        サブフォーム説明文
    </div>
    <div>
        <?=esc($subform->body)?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        設問項目
    </div>
    <div>
        <?if($subform_items){?>
            <table class="table">
                <tr>
                    <th>項目名</th>
                    <th>区分</th>
                    <th>必須</th>
                </tr>
                <?foreach($subform_items as $item){?>
                    <tr>
                        
                        <td><?=esc($item->name)?></td>
                        <td><?=esc($this->model("SubformItems")->params["section"][$item->section])?></td>
                        <td><?=esc($this->model("SubformItems")->params["required"][$item->required])?></td>
                    </tr>
                <?}?>
            </table>
        <?}?>
    </div>
</div>
<div class="text-center">
    <div class="mt-2">
        <button type="button" data-action="/subforms/edit/<?=esc($subform->id)?>" class="btn btn-outline-secondary href">編集する</button>
        <button type="button" data-confirm="このデータを削除してよろしいですか？" data-action="/subforms/del/<?=esc($subform->id)?>" class="btn btn-outline-danger href">削除する</button>
    </div>
    <div class="mt-2">
        <a href="/forms/detail/<?=esc($subform->form_id)?>" class="btn btn-link">フォーム詳細に戻る</a>
    </div>
</div>