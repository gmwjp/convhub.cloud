<form method="post" action="/forms/edit/<?=esc($form->id)?>" enctype="multipart/form-data">
    <?=$this->element("forms/form")?>
    <div class="text-center">
        <div class="mt-2">
            <button type="submit" class="btn btn-dark" name="execute" value="on">保存する</button>
        </div>
        <div class="mt-2">
            <a href="/forms/index" class="btn btn-link">一覧に戻る</a>
        </div>
    </div>
    <?=csrf()?>
</form>
<?=$this->element("template/subform")?>