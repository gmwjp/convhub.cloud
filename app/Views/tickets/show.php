<div class="row mt-2">
    <div class="col-sm-12 col-md-9">
        <h3><?=esc($ticket->title)?></h3>
        <div class="text-muted text-center"><small>問い合わせ内容によっては回答に時間が掛かる場合や、回答できない可能性がありますことをご了承ください</small></div>
        <div id="comments" class="px-1 mt-2">
            <div class="col-10 offset-2">
                <div class="alert alert-success">
                    <div class="clearfix">
                        <div class="float-left">
                            <label>あなた</label>
                        </div>
                        <div class="float-right">
                            <div><small><?=changeDate($ticket->created)?></small></div>
                        </div>
                    </div>
                    <?=nl2br(esc($ticket->body))?>
                </div>
            </div>
            <?foreach($comments as $comment){?>
                <?if($comment->user_section == "customer"){?>
                    <? //リクエスタからの回答 ?>
                    <div class="col-10 offset-2">
                        <div class="alert alert-success">
                            <div class="clearfix">
                                <div class="float-left">
                                    <label>あなた</label>
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
                    <? //運営事務局からの回答 ?>
                    <div class="col-10">
                        <div class="alert bg-white border">
                            <div class="clearfix">
                                <div class="float-left">
                                    <label>事務局</label>
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
        <div class="my-3">
            <form method="post" action="/tickets/show/<?=esc($crypt_ticket_id)?>">
                <textarea id="body" name="body" rows="4" class="form-control mt-1"></textarea>
                <div class="clearfix mt-1">
                    <div class="text-right">
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
    </div>
</div>
