<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/forms/add" class="btn btn-dark">新規追加</a>
</div>
<?if($forms){?>
<ul class="list-group mt-2">
    <?foreach($forms as $form){?>
    <a class="list-group-item list-group-item-action" href="/forms/detail/<?=esc($form->id)?>">
        <?=esc($form->name)?>
        <div class="text-muted">
            <small><?=cutWord(esc($form->body),100)?></small>
        </div>
    </a>
    <?}?>
</ul>
<?}?>
