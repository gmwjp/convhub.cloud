<?=$this->element("page_title")?>
<div class="row mt-2">
    <div class="col-3">
        <nav class="nav nav-pills flex-column">
            <a class="nav-link <?if($section=="none"){?>active<?}?>" href="/tickets/index/none">未割当<div class="float-right"><?=nf($ticket_nums["none"])?></div></a>
            <a class="nav-link <?if($section=="my_yet"){?>active<?}?>" href="/tickets/index/my_yet">あなたの未解決<div class="float-right"><?=nf($ticket_nums["my_yet"])?></div></a>
            <a class="nav-link <?if($section=="my_end"){?>active<?}?>" href="/tickets/index/my_end">あなたの解決済<div class="float-right"><?=nf($ticket_nums["my_end"])?></div></a>
            <a class="nav-link <?if($section=="all_yet"){?>active<?}?>" href="/tickets/index/all_yet">すべての未解決<div class="float-right"><?=nf($ticket_nums["all_yet"])?></div></a>
            <a class="nav-link <?if($section=="all_end"){?>active<?}?>" href="/tickets/index/all_end">すべての解決済<div class="float-right"><?=nf($ticket_nums["all_end"])?></div></a>
            <a class="nav-link <?if($section=="auto_end"){?>active<?}?>" href="/tickets/index/auto_end">自動解決済<div class="float-right"><?=nf($ticket_nums["auto_end"])?></div></a>
            <a class="nav-link <?if($section=="all"){?>active<?}?>" href="/tickets/index/all">すべてのチケット<div class="float-right"><?=nf($ticket_nums["all"])?></div></a>
        </nav>
    </div>
    <div class="col-9">
        <div class="text-right">
            <button type="button" class="btn <?if(request()->getGet("execute") != "on"){?>btn-light<?} else {?>btn-warning<?}?>" id="search_button"><span class="fal fa-search mr-1"></span>検索条件設定</button>
        </div>
        <div class="card mt-2 none" id="search_form">
            <div class="card-body">
                <form method="get" action="/tickets/index/<?=esc($section)?>">
                    <div class="form-group">
                        <div class="item-title">キーワード</div>
                        <div><input type="text" class="form-control" name="keyword" value="<?=esc(request()->getGet("keyword"))?>"></div>
                        <div class="text-muted"><small>件名・本文・メールアドレスを検索します。スペース区切りでAND条件になります。</small></div>
                    </div>
                    <div class="form-group">
                        <div class="item-title">担当者</div>
                        <div>
                            <select name="user_id" class="form-control custom-select" <?if(!empty($params[$section]["user_id"])){?>disabled<?}?>>
                                <option value=""></option>
                                <?foreach($users as $user){?>
                                    <option value="<?=esc($user->id)?>" <?if(request()->getGet("user_id")==$user->id){?>selected<?}?>><?=esc($user->nickname)?></option>
                                <?}?>
                                <option value="-1" <?if(request()->getGet("user_id")==-1){?>selected<?}?>>自動解決</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="item-title">状態</div>
                        <div>
                            <select name="status" class="form-control custom-select" <?if(!empty($params[$section]["status"])){?>disabled<?}?>>
                                <option value=""></option>
                                <?foreach($this->model("Tickets")->params["status"] as $key => $val){?>
                                    <option value="<?=esc($key)?>" <?if(request()->getGet("status")==$key){?>selected<?}?>><?=esc($val["text"])?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="item-title">フォーム</div>
                        <div>
                            <select name="form_id" class="form-control custom-select">
                                <option value=""></option>
                                <?foreach($forms as $form){?>
                                    <option value="<?=esc($form->id)?>" <?if(request()->getGet("form_id")==$form->id){?>selected<?}?>><?=esc($form->name)?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-dark" name="execute" value="on">検索を実行</button>
                        <a href="/tickets/index/<?=esc($section)?>" class="btn btn-outline-secondary">検索条件をクリア</a>
                    </div>
                    <?=csrf()?>
                </form>
            </div>
        </div>
        <ul class="list-group mt-2">
            <?foreach($tickets as $ticket){?>
                <a class="list-group-item list-group-item-action" href="/tickets/detail/<?=esc($ticket->id)?>">
                    <div class="clearfix">
                        <div class="float-right">
                            <span class="badge badge-<?=esc($this->model("Tickets")->params["status"][$ticket->status]["color"])?> p-1"><?=esc($this->model("Tickets")->params["status"][$ticket->status]["text"])?></span>
                        </div>
                        <div class="float-left">
                            <div class="">
                                <small><?=changeDate($ticket->created)?></small>
                                <?if($ticket->last_comment_user_id != ""){?>
                                <small class="ml-2">更新：<?=changeDate($ticket->last_comment_datetime)?></small>
                                <?}?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <?=esc($ticket->title)?><small class="text-muted ml-2"><?=esc($ticket->forms_name)?></small>
                    </div>
                </a>
            <?}?>
        </ul>
        <div class="text-center"><?=$this->library("pagenate")->paginate($page,$total,"/tickets/index/".esc($section),_def_page_num)?></div>
    </div>
</div>
<script>
$(function(){
    $("#search_button").click(function(){
        $("#search_form").fadeToggle();
    });
});
</script>