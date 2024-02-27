<?=$this->element("page_title")?>
<div class="text-right">
    <a href="/widgets/add/<?=esc($section)?>" class="btn btn-dark">新規追加</a>
</div>
<?if($widgets){?>
    <table class="table" id="table">
        <thead>
        <tr>
            <th>ウィジェット名</th>
            <th>表示数</th>
            <th>はい</th>
            <th>いいえ</th>
        </tr>
        </thead>
        <?foreach($widgets as $widget){?>
            <tr>
                <td><a href="/widgets/detail/<?=esc($widget->section)?>/<?=esc($widget->id)?>"><?=esc($widget->name)?></a></td>
                <td><?=esc($widget->view_count)?></td>
                <td><?=esc($widget->yes_count)?></td>
                <td><?=esc($widget->no_count)?></td>
            </tr>
        <?}?>    
    </table>
<?}?>
<script>
    $(document).ready(function() {
        $('#table').tablesorter();
    });
</script>