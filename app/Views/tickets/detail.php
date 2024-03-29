
<div class="row">
    <div class="col-sm-12 col-md-3" style="overflow:scroll;" id="header">
        <div class="card">
            <div class="card-body">
                <?if($form){?>
                    <a href="/forms/show/input/<?=esc($form->code)?>" target="_blank"><h4 class="page-title"><?=esc($form->name)?></h4></a>
                <?}?>
                <?if(!empty($subform)){?>
                <label><?=esc($subform->name)?></label>
                <?}?>
                <div class="group-item">
                    <div class="item-title">問い合わせメールアドレス</div>
                    <div><?=esc($ticket->mail)?></div>
                </div>
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
                <?if($ticket->notions !=""){?>
                <hr>
                <div><b>案内した記事：</b></div>
                <?foreach(json_decode($ticket->notions) as $notion){?>
                    <div class="mt-2"><a href="<?=esc($this->library("Crypt2")->decode($notion->url))?>" target="_blank"><?=esc($notion->title)?><i class="ml-1 fal fa-external-link"></i><?if($notion->read == 1){?><span class="badge badge-secondary ml-1">閲覧</span><?}?></a></div>
                <?}?>
                <?}?>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="clearfix">
            <div class="float-left">
                <h3><?=esc($ticket->title)?>&nbsp;<small>#<?=esc($ticket->id)?></small></h3>
            </div>
            <div class="float-right">
                <span class="p-1 badge badge-<?=esc($this->model("Tickets")->params["status"][$ticket->status]["color"])?>">
                    <?=esc($this->model("Tickets")->params["status"][$ticket->status]["text"])?>
                </span>
            </div>
        </div>
        <div style="overflow:scroll;" id="comments">
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
                    <?if(trim($ticket->attaches) != ""){?>
                        <div>
                            <hr>
                            <div class="clearfix">
                                <?foreach(json_decode($ticket->attaches) as $key => $file){?>
                                    <?=$this->element("attach",["section"=>"ticket","id" => $ticket->id,"no" => $key ,"file"=>$file])?>
                                <?}?>
                            </div>
                        </div>
                    <?}?>
                </div>
                <?if($ticket->user_id == -1){?>
                    <div class="text-center my-2">このチケットはユーザー自身が解決しました</div>
                <?}?>
            </div>
            <?foreach($comments as $comment){?>
                <?if($comment->user_section == "user"){?>
                    <? //運営事務局からの回答 ?>
                    <div class="col-10 offset-2">
                        <div class="alert <?if($comment->public_flg == 1){?>alert-success<?} else {?>alert-secondary<?}?>">
                            <div class="clearfix">
                                <div class="float-left">
                                    <label>回答者&nbsp;<small>[<?=esc($comment->users_nickname)?>]</small><?if($comment->public_flg == 0){?>&nbsp;<small class="text-muted">社内メモ</small><?}?></label>
                                </div>
                                <div class="float-right">
                                    <div><small><?=changeDate($ticket->created)?></small></div>
                                </div>
                            </div>
                            <div>
                                <?if($comment->public_flg == 0){?>
                                    <?=setTicketLink(nl2br(esc($comment->body)))?>
                                <?} else {?>
                                    <?=nl2br(esc($comment->body))?>
                                <?}?>
                                <?if(trim($comment->attaches) != ""){?>
                                    <div>
                                        <hr>
                                        <div class="clearfix">
                                            <?foreach(json_decode($comment->attaches) as $key => $file){?>
                                                <?=$this->element("attach",["section"=>"comment","id" => $comment->id,"no" => $key ,"file"=>$file])?>
                                            <?}?>
                                        </div>
                                    </div>
                                <?}?>
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
                                <?if(trim($comment->attaches) != ""){?>
                                    <div>
                                        <hr>
                                        <div class="clearfix">
                                            <?foreach(json_decode($comment->attaches) as $key => $file){?>
                                                <?=$this->element("attach",["section"=>"comment","id" => $comment->id,"no" => $key ,"file"=>$file])?>
                                            <?}?>
                                        </div>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    </div>
                <?}?>
            <?}?>
        </div>
        <div class="mt-3">
            <form method="post" action="/tickets/detail/<?=esc($ticket->id)?>" enctype="multipart/form-data">
                <div class="clearfix my-1">
                    <div class="float-left">
                        <select class="custom-select" name="public_flg" id="public_flg">
                            <option value="0">社内メモ</option>
                            <option value="1">パブリック返信</option>
                        </select>
                    </div>
                    <div class="float-right">

                        <button type="button" class="btn btn-sm btn-light" data-toggle="modal" data-target="#exampleModal">テンプレート選択<span class="fal fa-chevron-up ml-1"></span></button>
                    </div>
                </div>
                <textarea id="body" name="body" rows="4" class="form-control mt-1"><?=esc(request()->getPost("body"))?></textarea>
                <div class="clearfix mt-1">
                    <div class="float-left" >
                        <label for="upload" class="btn btn-light" id="file_num_button"  data-toggle="tooltip" data-placement="top" title="複数ファイルを選択可。PNG,JPG,GIFのみ。"><span class="fal fa-paperclip mr-1"></span><span id="file_num">添付ファイル</span></label>
                        <input type="file" id="upload" name="files[]" class="none" multiple>
                        <?=err($errors->getError("files"))?>
                    </div>
                    <div class="float-right">
                        <?=err($errors->getError("body"))?>
                        <button type="button" class="btn btn-dark submit" id="submit_button" data-confirm="送信してよろしいですか？">送信<span class="fal fa-send"></span></button>
                    </div>
                </div>
                <input type="hidden" name="execute" value="on">
                <?=csrf()?>
            </form>
        </div>
    </div>
    <div class="col-sm-12 col-md-3" id="footer" style="overflow:scroll;" >
        <div class="card">
            <div class="card-body">
                <form method="post" action="/tickets/change/<?=esc($ticket->id)?>">
                    <div class="group-item">
                        <div class="item-title">担当者</div>
                        <div>
                            <select class="custom-select inline" name="user_id"  id="user_id_form">
                                <option value=""></option>
                                <?foreach($users as $user){?>
                                    <option value="<?=esc($user->id)?>" <?if($user->id == $ticket->user_id){?>selected<?}?>><?=esc($user->nickname)?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="group-item">
                        <div class="item-title">状態</div>
                        <div>
                            <select class="custom-select inline" name="status"  id="status_form">
                                <?foreach($this->model("Tickets")->params["status"] as $key => $status){?>
                                    <?if($key != 0){?>
                                    <option value="<?=esc($key)?>" <?if($key == $ticket->status){?>selected<?}?>><?=esc($status["text"])?></option>
                                    <?}?>
                                <?}?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <?if($old_tickets){?>
            <h4 class="mt-3">過去の問い合わせ</h4>
            <div>
                <ul class="list-group">
                    <?foreach($old_tickets as $t){?>
                        <a class="list-group-item list-group-item-action" href="/tickets/detail/<?=esc($t->id)?>" target="_blank">
                            <div class="clearfix">
                                <div class="float-left text-muted">
                                    <small>#<?=esc($t->id)?>&nbsp;<?=changeDate(esc($t->created))?></small>
                                </div>
                                <div class="float-right">
                                    <span class="badge badge-<?=esc($this->model("Tickets")->params["status"][$t->status]["color"])?> p-1"><?=esc($this->model("Tickets")->params["status"][$t->status]["text_mini"])?></span>
                                </div>
                            </div>
                            <?=esc($t->title)?>
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
    $("body").css({
        height : $(window).height(),
        overflow : "hidden"
    });
    $("#header").css({
        height: ($(window).height() - 120) + "px"
    });
    $("#footer").css({
        height: ($(window).height() - 120) + "px"
    });
    $("#comments").css({
        height: ($(window).height() - 380) + "px"
    });
    $("#comments").scrollTop($("#comments")[0].scrollHeight);

}
function setTextform(){
    if($("#public_flg").val() == 1){
        $("#body").css({
            backgroundColor:"#fff"
        });
        $("#body").attr("placeholder","問い合わせユーザーに送信されます");
        $("#submit_button").removeClass("btn-secondary").addClass("btn-dark");
        $("#submit_button").html("パブリック送信");
        $("#submit_button").data("confirm","この問い合わせユーザーに返信します。\nメールが送信されますがよろしいですか？");
    } else {
        $("#body").css({
            backgroundColor:"#fffacd"
        });
        $("#body").attr("placeholder","問い合わせユーザーには閲覧できないコメントを記述できます");
        $("#submit_button").addClass("btn-secondary").removeClass("btn-dark");
        $("#submit_button").data("confirm","送信してよろしいですか？");
        $("#submit_button").html("社内メモ送信");
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
    
    $("#upload").change(function() {
        var fileCount = $(this).get(0).files.length;
        if(fileCount > 0){
            $("#file_num_button").addClass("btn-warning").removeClass("btn-light");
            $("#file_num").html("選択ファイル："+fileCount);
        } else {
            $("#file_num_button").removeClass("btn-warning").addClass("btn-light");
            $("#file_num").html("添付ファイル");
        }
    });
    $("#status_form").change(function(){
        postData("/tickets/change/status/<?=esc($ticket->id)?>",{value:$(this).val()},function(data){
            var response = JSON.parse(data);
            if(response.result == "success"){
                info("状態を変更しました");
            } else {
                error(response.message);
            }
        });
    });
    $("#user_id_form").change(function(){
        postData("/tickets/change/user_id/<?=esc($ticket->id)?>",{value:$(this).val()},function(data){
            var response = JSON.parse(data);
            if(response.result == "success"){
                info("担当者を変更しました");
            } else {
                error(response.message);
            }
        });
    });
});
</script>