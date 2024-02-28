<div class="text-center">
    <div id="feedback"  class="mt-3">
        <div>この記事は役に立ちましたか？</div>
        <div class="mt-2">
            <button type="button" class="btn btn-dark btn-sm feedback_button" style="width:100px;" data-answer="yes" id="yes_button">はい</button>
            <button type="button" class="btn btn-secondary btn-sm ms-3 feedback_button" style="width:100px;" data-answer="no" id="no_button">いいえ</button>
        </div>
    </div>
    <div class="mt-2 text-secondary">
        <small>
            <span id="all_count"><?=esc($widget->yes_count + $widget->no_count)?></span>人中
            <span id="yes_count"><?=esc($widget->yes_count)?></span>人がこの記事が役に立ったと言っています
        </small>
    </div>
</div>
<form action="/widgets/exec/<?=esc($widget->code)?>?action=view" method="post">
    <button type="submit">send</button>
    <?=csrf()?>
</form>
<script>
$(function(){
    //閲覧数をカウント
    postData("/widgets/exec/<?=esc($widget->code)?>?action=view",{});
    //フィードバック送信
    $(".feedback_button").click(function(){
        postData("/widgets/exec/<?=esc($widget->code)?>?action=answer&param="+$(this).data("answer"),{},function(data){
            $("#feedback").html("フィードバックいただきありがとうございます");
            var res = JSON.parse(data);
            $("#all_count").html(res.data.all_count);
            $("#yes_count").html(res.data.yes_count);
        });
    });
});
</script>