<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/templates/add" class="btn btn-dark">新規追加</a>
</div>
<?if($templates){?>
<ul class="list-group mt-2">
    <?foreach($templates as $template){?>
    <a class="list-group-item list-group-item-action" href="/templates/detail/<?=esc($template->id)?>">
        <?=esc($template->name)?>
        <div class="text-muted">
            <small><?=cutWord(esc($template->body),100)?></small>
        </div>
    </a>
    <?}?>
</ul>
<?}?>
