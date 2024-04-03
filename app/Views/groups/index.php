<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/groups/add" class="btn btn-dark">新規追加</a>
</div>
<?if($groups){?>
<ul class="list-group mt-2">
    <?foreach($groups as $group){?>
    <a class="list-group-item list-group-item-action" href="/groups/detail/<?=esc($group->id)?>">
        <?=esc($group->name)?>
    </a>
    <?}?>
</ul>
<?}?>
