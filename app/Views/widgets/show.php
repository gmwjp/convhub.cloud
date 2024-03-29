<div id="feedback"  class="mt-3 d-none">
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
<?if($form){?>
<div class="mt-3">
    <div><small>他に質問がございましたら、<a href="/forms/show/input/<?=esc($form->code)?>" target="_blank">お問い合わせ</a>ください</small></div>
</div>
<?}?>
<script>
$(function(){
    
    //閲覧数をカウント
    if(!sessionStorage.getItem('view_<?=esc($widget->code)?>')){
        postData("/widgets/exec/<?=esc($widget->code)?>?action=view",{},function(){
            sessionStorage.setItem('view_<?=esc($widget->code)?>', true);
        });
    }
    if(sessionStorage.getItem('answer_<?=esc($widget->code)?>')){
        $("#feedback").html("フィードバックいただきありがとうございます");
        $("#feedback").removeClass("d-none");
    } else {
        $("#feedback").removeClass("d-none");
    }
    //フィードバック送信
    $(".feedback_button").click(function(){
        postData("/widgets/exec/<?=esc($widget->code)?>?action=answer&param="+$(this).data("answer"),{},function(data){
            sessionStorage.setItem('answer_<?=esc($widget->code)?>', true);
            $("#feedback").html("フィードバックいただきありがとうございます");
            var res = JSON.parse(data);
            $("#all_count").html(res.data.all_count);
            $("#yes_count").html(res.data.yes_count);
        });
    });
});
</script>