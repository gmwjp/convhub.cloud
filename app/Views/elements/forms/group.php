<div class="form-group">
    <div class="item-title">
        グループ名
    </div>
    <div>
        <input type="text" name="name" class="form-control" value="<?=esc(request()->getPost("name"))?>">
        <?=err($errors->getError("name"))?>
    </div>
</div>