<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/templates/add" class="btn btn-dark">新規追加</a>
</div>
<?if($templates){?>
    <table class="mt-2 table" >
        <tr>
            <th>サブフォーム名</th>
            <th></th>
        </tr>
        <?foreach($templates as $template){?>
            <tr>
                <td><a href="/templates/detail/<?=esc($template->id)?>"><?=esc($template->name)?></a></td>
                <td>
                    <button type="button" class="btn btn-sm btn-light href" data-action="/templates/up/<?=esc($template->id)?>">上へ</button>
                    <button type="button" class="btn btn-sm btn-light href" data-action="/templates/down/<?=esc($template->id)?>">下へ</button>
                </td>
            </tr>
        <?}?>
    </div>
<?}?>
