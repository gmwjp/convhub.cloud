
<div class="row">
    <div class="col-sm-12 col-md-9">
        <h3><?=esc($ticket->title)?></h3>
        <div style="overflow:scroll;" id="comments" class="px-1">
            <div class="col-10">
                <div class="alert bg-white border">
                    <div class="clearfix">
                        <div class="float-left">
                            <label><?=esc(requester_name($ticket->mail))?></label>
                        </div>
                        <div class="float-right">
                            <div><small><?=changeDate($ticket->created)?></small></div>
                        </div>
                    </div>
                    <?=nl2br(esc($ticket->body))?>
                </div>
            </div>
            <?foreach($comments as $comment){?>
                <?if($comment->user_section == "user"){?>
                    <? //運営事務局からの回答 ?>
                    <div class="col-10 offset-2">
                        <div class="alert <?if($comment->public_flg == 1){?>alert-success<?} else {?>alert-secondary<?}?>">
                            <div class="clearfix">
                                <div class="float-left">
                                    <label>回答者<?if($comment->public_flg == 0){?>&nbsp;<small>[社内メモ]</small><?}?></label>
                                </div>
                                <div class="float-right">
                                    <div><small><?=changeDate($ticket->created)?></small></div>
                                </div>
                            </div>
                            <div>
                                <?=nl2br(esc($comment->body))?>
                            </div>
                        </div>
                    </div>
                <?} else {?>
                    <? //リクエスタからの回答 ?>
                    <div class="col-10">
                        <div class="alert bg-white border">
                            <div class="clearfix">
                                <div class="float-left">
                                    <label><?=esc(requester_name($ticket->mail))?></label>
                                </div>
                                <div class="float-right">
                                    <div><small><?=changeDate($ticket->created)?></small></div>
                                </div>
                            </div>
                            <div>
                                <?=nl2br(esc($comment->body))?>
                            </div>
                        </div>
                    </div>
                <?}?>
            <?}?>
        </div>
        <div class="mt-3">
            <form method="post" action="/tickets/detail/<?=esc($ticket->id)?>">
                <div class="clearfix my-1">
                    <div class="float-left">
                        <select class="custom-select" name="public_flg" id="public_flg">
                            <option value="0">社内メモ</option>
                            <option value="1">パブリック返信</option>
                        </select>
                    </div>
                    <div class="float-right">
                    <button type="button" class="btn btn-sm btn-light" data-toggle="modal" data-target="#exampleModal">テンプレート選択</button>
                    </div>
                </div>
                <textarea id="body" name="body" rows="4" class="form-control mt-1"></textarea>
                <div class="clearfix mt-1">
                    <div class="text-right">
                        <select class="custom-select inline" name="status" style="width:200px">
                            <?foreach($this->model("Tickets")->params["status"] as $key => $status){?>
                                <?if($key != 0){?>
                                <option value="<?=esc($key)?>"><?=esc($status["text"])?></option>
                                <?}?>
                            <?}?>
                        </select>
                        <button type="button" class="btn btn-sm btn-dark submit" data-confirm="送信してよろしいですか？">送信<span class="fal fa-send"></span></button>
                    </div>
                </div>
                <input type="hidden" name="execute" value="on">
                <?=csrf()?>
            </form>
        </div>
    </div>
    <div class="col-sm-12 col-md-3">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"><?=esc($form->name)?></h4>
                <?if($ticket->subform_id){?>
                <label><?=esc($subform->name)?></label>
                <?}?>
                <?if($ticket_form_items){?>
                    <?foreach($ticket_form_items as $item){?>
                        <div class="group-item">
                            <div class="item-title"><?=esc($item->title)?></div>
                            <div><?=esc($item->value)?></div>
                        </div>
                    <?}?>
                <?}?>
                <?if($ticket_subform_items){?>
                    <?foreach($ticket_subform_items as $item){?>
                        <div class="group-item">
                            <div class="item-title"><?=esc($item->title)?></div>
                            <div><?=esc($item->value)?></div>
                        </div>
                    <?}?>
                <?}?>
            </div>
        </div>
        <?if($old_tickets){?>
            <h4 class="mt-3">過去の問い合わせ</h4>
            <div>
                <ul class="list-group">
                    <?foreach($old_tickets as $ticket){?>
                        <a class="list-group-item list-group-item-action" href="/tickets/detail/<?=esc($ticket->id)?>">
                            <div class="float-right">
                                <span class="badge badge-<?=esc($this->model("Tickets")->params["status"][$ticket->status]["color"])?> p-1"><?=esc($this->model("Tickets")->params["status"][$ticket->status]["text_mini"])?></span>
                            </div>
                            <?=esc($ticket->title)?>
                        </a>
                    <?}?>
                </ul>
            </div>
        <?}?>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-custom" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">テンプレート選択</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?if($templates){?>
            <div class="list-group">
            <?foreach($templates as $template){?>
                <div class="list-group-item">
                    <div class="clearfix">
                        <div class="float-left">
                            <?=esc($template->name)?>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-dark btn-sm template_select_button" data-id="<?=esc($template->id)?>">選択</button>
                        </div>
                    </div>
                    <div class="text-muted"><small id="template_<?=esc($template->id)?>"><?=esc($template->body)?></small></div>
                </div>
            <?}?>
            </div>
        <?}?>
      </div>
    </div>
  </div>
</div>
<script>
function resizeForm(){
    $("#comments").css({
        height: ($(window).height() - 430) + "px"
    });
    $("#comments").scrollTop($("#comments")[0].scrollHeight);

}
function setTextform(){
    if($("#public_flg").val() == 1){
        $("#body").css({
            backgroundColor:"#fff"
        });
        $("#body").attr("placeholder","リクエスタに送信されます");
    } else {
        $("#body").css({
            backgroundColor:"#fffacd"
        });
        $("#body").attr("placeholder","リクエスタには閲覧できないコメントを記述できます");
    }
}
$("#public_flg").change(function(){
    setTextform();
});
$(".template_select_button").click(function(){
    var id = $(this).data("id");
    var body = $("#template_"+id).html();
    $("#body").val(body);
    $("#exampleModal").modal("hide");
    $("#body").focus();
})
$(window).resize(function() {
    resizeForm();
});
$(document).ready(function() {
    resizeForm();
    setTextform();
});
</script>