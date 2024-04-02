<?=$this->element("page_title")?>
<div class="row">
    <div class="col-6 text-left">
        <select name="order" class="form-control custom-select" id="order">
            <option value="view" <?if(request()->getGet("order") == "view"){?>selected<?}?>>表示数順</option>
            <option value="yes" <?if(request()->getGet("order") == "yes"){?>selected<?}?>>高評価順</option>
            <option value="no" <?if(request()->getGet("order") == "no"){?>selected<?}?>>低評価順</option>
        </select>
    </div>
    <div class="col-6 text-right">
        <form action="/widgets/sync_data/<?=esc($form->id)?>" method="post">
            <button type="submit" class="btn btn-dark" name="execute" value="on">同期を実行する</button>
            <?=csrf()?>
        </form>
    </div>
</div>
<?if($widgets){?>
    <table class="table mt-2" id="table">
        <thead>
        <tr>
            <th>ウィジェット名</th>
            <th>表示数</th>
            <th>はい</th>
            <th>いいえ</th>
            <th></th>
        </tr>
        </thead>
        <?foreach($widgets as $widget){?>
            <tr>
                <td>
                    <div>
                        <a href="/widgets/detail/<?=esc($widget->id)?>" data-toggle="tooltip" data-placement="top" title="<?=esc($widget->name)?>"><?=cutWord(esc($widget->name),20)?></a>
                        <sapn class="ml-2"><button type="button" data-toggle="tooltip" data-placement="top" title="ウィジェットURLをコピー" class="btn btn-light btn-sm copy_button" data-value="<?=$_SERVER["SITE_URL"]?>/widgets/show/<?=esc($widget->code)?>"><span class="fal fa-copy"></span></button></span>
                        <?if($widget->notion_url !=""){?>
                            <sapn class="ml-1"><a href="<?=esc($widget->notion_url)?>" target="_blank" data-toggle="tooltip" data-placement="top" title="記事を管理" class="btn btn-light btn-sm"><span class="fal fa-external-link"></span></a></span>
                        <?}?>
                        <input type="hidden" id="widget_url_<?=esc($widget->id)?>" class="form-control" readonly="true" value="<?=$_SERVER["SITE_URL"]?>/widgets/show/<?=esc($widget->code)?>">
                    </div>
                </td>
                <td><?=esc($widget->view_count)?></td>
                <td><?=esc($widget->yes_count)?></td>
                <td><?=esc($widget->no_count)?></td>
                <td></td>
            </tr>
        <?}?>    
    </table>
<?}?>
<script>
    $(function(){
        $("#order").change(function(){
            location.href = "/forms/widgets/<?=esc($form->id)?>?order="+$(this).val();
        })
    });
</script>