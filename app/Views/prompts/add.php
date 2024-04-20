<?=$this->element("page_title")?>
<form method="post" action="/prompts/add">
    <?=$this->element("forms/prompt")?>
    <div class="text-center">
        <div class="mt-2">
            <button type="submit" class="btn btn-dark" name="execute" value="on">保存する</button>
        </div>
        <div class="mt-2">
            <a href="/prompts/index" class="btn btn-link">一覧に戻る</a>
        </div>
    </div>
    <?=csrf()?>
</form>