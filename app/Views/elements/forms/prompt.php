<div class="form-group">
    <div class="item-title">
        プロンプト名
    </div>
    <div>
        <input type="text" name="name" class="form-control" value="<?=esc(request()->getPost("name"))?>">
        <?=err($errors->getError("name"))?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        本文
    </div>
    <div>
        <textarea name="body" class="form-control" rows="4"><?=esc(request()->getPost("body"))?></textarea>
        <?=err($errors->getError("body"))?>
    </div>
</div>
