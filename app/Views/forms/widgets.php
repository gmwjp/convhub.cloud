<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/widgets/sync_data/<?=esc($form->id)?>" class="btn btn-dark">同期を実行する</a>
</div>
<?if($widgets){?>
    <table class="table mt-2" id="table">
        <thead>
        <tr>
            <th>ウィジェット名</th>
            <th>同期日時</th>
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
                        <a href="/widgets/detail/<?=esc($widget->id)?>"><?=esc($widget->name)?></a>
                        <sapn class="ml-2"><button type="button" data-toggle="tooltip" data-placement="top" title="ウィジェットURLをコピー" class="btn btn-light btn-sm copy_button" data-widget-url="<?=$_SERVER["SITE_URL"]?>/widgets/show/<?=esc($widget->code)?>"><span class="fal fa-copy"></span></button></span>
                        <?if($widget->notion_url !=""){?>
                            <sapn class="ml-1"><a href="<?=esc($widget->notion_url)?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Notion記事を開く" class="btn btn-light btn-sm"><span class="fal fa-external-link"></span></a></span>
                        <?}?>
                        <input type="hidden" id="widget_url_<?=esc($widget->id)?>" class="form-control" readonly="true" value="<?=$_SERVER["SITE_URL"]?>/widgets/show/<?=esc($widget->code)?>">
                    </div>
                </td>
                <td><?=esc($widget->modified)?></td>
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