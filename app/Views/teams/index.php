<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/users/invite" class="btn btn-dark">新規招待</a>
</div>
<ul class="list-group mt-2">
    <?foreach($users as $user){?>
        <a class="list-group-item list-group-item-action" href="/users/detail/<?=esc($user->id)?>">
            <div class="float-right">
                <?if($user->temp == 1){?>
                    <span class="badge badge-secondary p-1">招待中</span>
                <?}?>
            </div>
            <div class="text-muted">
                <small>最終ログイン：<?=changeDate($user->created)?></small>
            </div>
            <?=esc($user->nickname)?>
        </a>
    <?}?>
</ul>