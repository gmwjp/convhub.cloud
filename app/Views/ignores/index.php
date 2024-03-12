<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/ignores/add" class="btn btn-dark">新規追加</a>
</div>
<?if($ignores){?>
    <table class="table">
        <tr>
            <th>URL</th>
            <th></th>
        </tr>
        <?foreach($ignores as $ignore){?>
            <tr>
                <td><a href="<?=esc($ignore->url)?>" target="_blank"><?=esc($ignore->url)?><i class="ml-1 fal fa-external-link"></i></a></td>
                <td><button type="button" data-confirm="この除外設定を削除してもよろしいですか？" data-action="/ignores/del/<?=esc($ignore->id)?>" class="btn btn-light href"><i class="fal fa-trash"></i></button></td>
            </tr>
        <?}?>
    </table>
<?}?>
