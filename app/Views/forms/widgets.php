<?=$this->element("page_title")?>
<div class="text-right">
    <form action="/widgets/sync_data/<?=esc($form->id)?>" method="post">
        <button type="submit" class="btn btn-dark" name="execute" value="on">同期を実行する</button>
        <?=csrf()?>
    </form>
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
                        <sapn class="ml-2"><button type="button" data-toggle="tooltip" data-placement="top" title="ウィジェットURLをコピー" class="btn btn-light btn-sm copy_button" data-widget-url="<?=$_SERVER["SITE_URL"]?>/widgets/show/<?=esc($widget->code)?>"><span class="fal fa-copy"></span></button></span>
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
    $(document).ready(function() {
        $('.copy_button').click(function(){
            var copyText = $(this).data("widget-url");
            var temp = $("<input>");
            $('body').append(temp);
            temp.val(copyText).select();
            document.execCommand("copy");
            temp.remove();
            info("コピーしました");
        });

    });
</script>