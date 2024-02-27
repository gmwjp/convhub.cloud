<form method="post" action="/subforms/add/<?=esc($form->id)?>">
    <?=$this->element("forms/subform")?>
    <div class="text-center">
        <div class="mt-2">
            <button type="submit" class="btn btn-dark" name="execute" value="on">保存する</button>
        </div>
        <div class="mt-2">
            <a href="/forms/detail/<?=esc($form->id)?>" class="btn btn-link">一覧に戻る</a>
        </div>
    </div>
</form>
<?=$this->element("template/subform")?>