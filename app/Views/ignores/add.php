<?=$this->element("page_title")?>
<form method="post" action="/ignores/add">
    <?=$this->element("forms/ignore")?>
    <div class="text-center">
        <div class="mt-2">
            <button type="submit" class="btn btn-dark" name="execute" value="on">保存する</button>
        </div>
        <div class="mt-2">
            <a href="/ignores/index" class="btn btn-link">一覧に戻る</a>
        </div>
    </div>
    <?=csrf()?>
</form>