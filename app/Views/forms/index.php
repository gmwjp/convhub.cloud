<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/forms/add" class="btn btn-dark">新規追加</a>
</div>
<?if($forms){?>
    <table class="table mt-2">
        <tr>
            <th>フォーム名</th>
            <th></th>
        </tr>
        <?foreach($forms as $form){?>
            <tr>
                <td><a href="/forms/detail/<?=esc($form->id)?>"><?=esc($form->name)?></a></td>
                <td>
                    <sapn class="mr-1"><a href="/forms/show/input/<?=esc($form->code)?>" target="_blank" class="btn btn-light btn-sm">フォームを表示<span class="fal fa-external-link ml-1"></span></a></span>
                    <sapn class="mr-1"><a href="/forms/widgets/<?=esc($form->id)?>" target="_blank" class="btn btn-light btn-sm">ウィジェット管理</a></span>
                </td>
            </tr>
        <?}?>
    </table>
<?}?>