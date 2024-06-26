<style>
.break-word {
    overflow-wrap: break-word;
}
blockquote {
    border-left: 5px solid #ccc;
    padding-left: 10px;
    margin-left: 0;
    color: #666;
}
#quoteButton {
    display: none;
    position: absolute;
}
</style>
<div class="row mt-2">
    <button id="quoteButton" class="btn btn-secondary btn-sm"><span class="fa fa-quote-right mr-1"></span>メッセージ引用</button>

    <div class="col-sm-12 col-md-9">
        <h3><?=esc($ticket->title)?></h3>
        <div class="text-muted text-center"><small>問い合わせ内容によっては回答に時間が掛かる場合や、回答できない可能性がありますことをご了承ください</small></div>
        <div id="comments" class="mt-2">
            <div class="col-10 offset-2">
                <div class="alert alert-success break-word">
                    <div class="clearfix">
                        <div class="float-left">
                            <label>あなた</label>
                        </div>
                        <div class="float-right">
                            <div><small><?=changeDate($ticket->created)?></small></div>
                        </div>
                    </div>
                    <?=convertQuoteTags(setUrlLink(nl2br(esc($ticket->body))))?>
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
            </div>
            <?foreach($comments as $comment){?>
                <?if($comment->user_section == "customer"){?>
                    <? //リクエスタからの回答 ?>
                    <div class="col-10 offset-2">
                        <div class="alert alert-success break-word">
                            <div class="clearfix">
                                <div class="float-left">
                                    <label>あなた</label>
                                </div>
                                <div class="float-right">
                                    <div><small><?=changeDate($comment->created)?></small></div>
                                </div>
                            </div>
                            <div style="overflow-wrap: break-word;">
                                <?=convertQuoteTags(setUrlLink(nl2br(esc($comment->body))))?>
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
                    <? //運営事務局からの回答 ?>
                    <div class="col-10">
                        <div class="alert bg-white border break-word">
                            <div class="clearfix">
                                <div class="float-left">
                                    <label>事務局</label>
                                </div>
                                <div class="float-right">
                                    <div><small><?=changeDate($comment->created)?></small></div>
                                </div>
                            </div>
                            <div style="overflow-wrap: break-word;">
                                <?=convertQuoteTags(setUrlLink(nl2br(esc($comment->body))))?>
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
        <div class="my-3">
            <form method="post" action="/tickets/show/<?=esc($crypt_ticket_id)?>" enctype="multipart/form-data">
                <textarea id="body" name="body" rows="4" class="form-control mt-1" placeholder="返答を入力して送信できます"><?=esc(request()->getPost("body"))?></textarea>
                <?=err($errors->getError("body"))?>
                <div class="clearfix mt-1">
                    <div class="float-left">
                        <label for="upload" class="btn btn-light" id="file_num_button"><span class="fal fa-paperclip mr-1"></span><span id="file_num">添付ファイル</span></label>
                        <input type="file" id="upload" name="files[]" class="d-none" multiple>
                        <?=err($errors->getError("files"))?>
                        <span class="text-muted ml-1"><small>複数ファイルを選択できます。PNG,JPG,GIFのみ</small></span>
                    </div>
                    <div class="float-right">
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
                <?if(!empty($subform)){?>
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
        <?=$this->element("most_widget")?>
    </div>
</div>
<script>
$(function(){
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
    $(document).mouseup(function() {
        const selectedText = window.getSelection().toString();
        const $quoteButton = $('#quoteButton');
        if (selectedText) {
            const rect = window.getSelection().getRangeAt(0).getBoundingClientRect();
            $quoteButton.css({
                top: rect.bottom + window.scrollY-70,
                left: rect.left + window.scrollX,
                zIndex : 1000,
                width : 200,
                display: 'block'
            });
        } else {
            $quoteButton.hide();
        }
    });
    $('#quoteButton').click(function() {
        const selectedText = window.getSelection().toString();
        const $messageTextArea = $('#body');
        $messageTextArea.val($messageTextArea.val() + `[quote]${selectedText}[/quote]\n`);
        $(this).hide();
        var len = $('#body').val().length;
        $('#body').focus().get(0).setSelectionRange(len, len);
    });
});
</script>