<style>
#comment_box {
    position: fixed; /* 要素を固定位置に設定 */
    bottom: 0;       /* 下端に配置 */
    left: 0;         /* 左端から開始 */
    right: 0;        /* 右端まで広げる */
    z-index: 1000;   /* 上に表示するためのz-index */
    background-color: white; /* 背景色は白（必要に応じて変更可能） */
    box-shadow: 0 -2px 5px rgba(0,0,0,0.1); /* 上側に影をつける */
    padding: 10px 20px; /* パディングを追加 */
}
#content {
    padding-bottom: 200px;
}
</style>
<div class="row" id="content">
    <div class="col-sm-12 col-md-3" id="header">
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
                    <a href="#">
                        <div class="list-group-item-action p-1 clearfix copy_button" data-toggle="tooltip" data-placement="top" title="コピー" data-value="<?=esc($ticket->mail)?>">
                            <div class="float-left">
                                <?=esc($ticket->mail)?>
                            </div>
                            <div class="float-right">
                                <span class="fal fa-copy"></span>
                            </div>
                        </div>
                    </a>
                    <div class="mt-1 text-right"><a href="https://support-sys.g-m-w.jp/datas/index/all/users?mail=<?=esc($ticket->mail)?>&match=1&execute=on" class="btn btn-light btn-sm" target="_blank">ユーザー検索<span class="ml-1 fal fa-external-link"></span></a></div>
                </div>

                <?if($ticket_form_items){?>
                    <?foreach($ticket_form_items as $item){?>
                        <div class="group-item mb-2">
                            <div class="item-title"><?=esc($item->title)?></div>
                            <a href="#">
                                <div class="list-group-item-action p-1 clearfix copy_button" data-toggle="tooltip" data-placement="top" title="コピー" data-value="<?=esc($item->value)?>">
                                    <div class="float-left">
                                        <?=esc($item->value)?>
                                    </div>
                                    <div class="float-right">
                                        <span class="fal fa-copy"></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?}?>
                <?}?>
                <?if($ticket_subform_items){?>
                    <?foreach($ticket_subform_items as $item){?>
                        <div class="group-item">
                            <div class="item-title"><?=esc($item->title)?></div>
                            <?if($item->value !=""){?>
                            <a href="#">
                                <div class="list-group-item-action p-1 clearfix copy_button" data-toggle="tooltip" data-placement="top" title="コピー" data-value="<?=esc($item->value)?>">
                                    <div class="float-left">
                                        <?=esc($item->value)?>
                                    </div>
                                    <div class="float-right">
                                        <span class="fal fa-copy"></span>
                                    </div>
                                </div>
                            </a>
                            <?}?>
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
                <?if($ticket->query_params){?>
                    <hr>
                    <a href="#">
                        <div class="list-group-item-action p-1 clearfix" id="metadata_button" data-toggle="tooltip" data-placement="top" title="metadataを表示">
                            <div class="float-left"><b>metadata</b></div>
                            <div class="float-right"><span class="fal fa-caret-square-down"></span></div>
                        </div>
                        <ul id="metadata" class="none alert alert-secondary border">
                            <?foreach(json_decode($ticket->query_params) as $key => $val){?>
                                <li><?=esc($key)?>：<?=esc($val)?></li>
                            <?}?>
                        </ul>
                    </a>
                <?}?>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="clearfix">
            <div class="float-left">
                <h4><?=esc($ticket->title)?>&nbsp;<small>#<?=esc($ticket->id)?></small></h4>
            </div>
            <div class="float-right">
                <span class="p-1 badge badge-<?=esc($this->model("Tickets")->params["status"][$ticket->status]["color"])?>">
                    <?=esc($this->model("Tickets")->params["status"][$ticket->status]["text"])?>
                </span>
            </div>
        </div>
        <div id="comments">
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
                    <?=setUrlLink(nl2br(esc($ticket->body)))?>
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
            <?if($ticket->summary_flg == 1 && $ticket->summary !=""){?>
                <? // 要約表示 ?>
                <div class="col-10 offset-2">
                    <div class="alert alert-secondary">
                        <div class="clearfix">
                            <div class="float-left">
                                <label>ConvHUB system（自動要約）&nbsp;<small class="text-muted">社内メモ</small></label>
                            </div>
                        </div>
                        <div>
                            <?=setTicketLink(setUrlLink(nl2br(esc($ticket->summary))))?>
                        </div>
                    </div>
                </div>
            <?}?>
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
                                    <div><small><?=changeDate($comment->created)?></small></div>
                                </div>
                            </div>
                            <div style="overflow-wrap: break-word;">
                                <?if($comment->public_flg == 0){?>
                                    <?=setTicketLink(setUrlLink(nl2br(esc($comment->body))))?>
                                <?} else {?>
                                    <?=setUrlLink(nl2br(esc($comment->body)))?>
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
                            <?if($comment->read_datetime !="" && $comment->public_flg != 0){?>
                            <div class="text-right mt-2"><small>既読：<?=changeDate($comment->read_datetime)?></small></div>
                            <?}?>
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
                                    <div><small><?=changeDate($comment->created)?></small></div>
                                </div>
                            </div>
                            <div style="overflow-wrap: break-word;">
                                <?=setUrlLink(nl2br(esc($comment->body)))?>
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
        <div class="mt-3" id="comment_box">
            <form method="post" action="/tickets/detail/<?=esc($ticket->id)?>" enctype="multipart/form-data" id="comment_form">
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
    <div class="col-sm-12 col-md-3" id="footer">
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
                        <a class="list-group-item list-group-item-action" href="/tickets/detail/<?=esc($t->id)?>" data-placement="top" data-trigger="hover" tabindex="0" data-toggle="popover" title="<?=esc($t->title)?>" data-content="<?=esc($t->body)?>">
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
        <div class="text-muted text-center mb-2"><small>クリックするとテキストボックスの末尾に文章が追加されます</small></div>
        <?if($templates){?>
            <div class="list-group">
            <?foreach($templates as $template){?>
                <a class="list-group-item template_select_button list-group-item-action" href="#" data-id="<?=esc($template->id)?>">
                    <div class="clearfix">
                        <div class="float-left">
                            <b><?=esc($template->name)?></b>
                        </div>
                    </div>
                    <div class="text-muted" style="max-height:40px;overflow:hidden;"><small id="template_<?=esc($template->id)?>"><?=esc($template->body)?></small></div>
                </a>
            <?}?>
            </div>
        <?}?>
      </div>
    </div>
  </div>
</div>
<script>
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
    if($("#body").val()==""){
        $("#body").val(body);
    } else {
        $("#body").val($("#body").val()+"\n"+body);
    }
    $("#exampleModal").modal("hide");
    setTimeout(function(){
        var len = $('#body').val().length;
        $('#body').focus().get(0).setSelectionRange(len, len);
    },500);
})
$(document).ready(function() {
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
    $("#comment_form").submit(function(e){
        e.preventDefault();
        var form = this;
        $.ajax({
            url: '/users/get_token',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                csrf_token_value = data.value;
                $('input[name="' + csrf_token_name + '"]').val(csrf_token_value); 
                // トークンがセットされた後、フォームをプログラム的に送信
                form.submit();
            },
            error: function() {
                error('CSRF取得エラー');
            }
        });
    });
    $("#metadata_button").click(function(){
        $("#metadata").fadeToggle();
    });
    // テキストエリアのサイズ変更を検出してパディングを調整する関数
    function adjustPadding() {
        var textareaHeight = $('#comment_box').outerHeight(); // テキストエリアの現在の高さを取得
        console.log(textareaHeight );
        $('#content').css('padding-bottom', textareaHeight); // コンテンツのpadding-bottomを更新
    }
    // テキストエリアのサイズ変更イベントに反応する
    $('#body').on('mouseup keyup', function() {
        adjustPadding();
    });
    // ページ読み込み時に一度パディングを調整
    adjustPadding();
});
</script>