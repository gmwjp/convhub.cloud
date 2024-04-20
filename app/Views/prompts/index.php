<?=$this->element("navi/answer",["index"=>"prompt"])?>
<div class="text-right">
    <a href="/prompts/add" class="btn btn-dark">新規追加</a>
</div>
<?if($prompts){?>
    <table class="mt-2 table" >
        <?foreach($prompts as $prompt){?>
            <tr>
                <td><a href="/prompts/detail/<?=esc($prompt->id)?>"><?=esc($prompt->name)?></a></td>
                <td>
                    <button type="button" class="btn btn-sm btn-light href" data-action="/prompts/up/<?=esc($prompt->id)?>">上へ</button>
                    <button type="button" class="btn btn-sm btn-light href" data-action="/prompts/down/<?=esc($prompt->id)?>">下へ</button>
                </td>
            </tr>
        <?}?>
    </div>
<?}?>
