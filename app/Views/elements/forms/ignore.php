<div class="form-group">
    <div class="item-title">
        ウィジェットの設置したくないNotionのページURL
    </div>
    <div>
        <input type="text" name="url" class="form-control" value="<?=esc(request()->getPost("url"))?>">
        <?=err($errors->getError("url"))?>
        <div class="text-muted"><small>公開ページではなく、管理ページのURLを入力して下さい</small></div>
    </div>
</div>