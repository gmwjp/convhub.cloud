<div class="form-group">
    <div class="item-title">
        ウィジェット名
    </div>
    <div>
        <input type="text" name="name" class="form-control" value="<?=esc(request()->getPost("name"))?>">
        <?=err($errors->getError("name"))?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        問い合わせフォームへのリンク
    </div>
    <div>
        <select name="form_id" class="form-control custom-select">
            <option value="">設置しない</option>
            <?foreach($forms as $form){?>
                <option value="<?=esc($form->id)?>" <?if(request()->getPost("form_id") == $form->id){?>selected<?}?>><?=esc($form->name)?></option>
            <?}?>
        </select>
        <?=err($errors->getError("form_id"))?>
    </div>
</div>