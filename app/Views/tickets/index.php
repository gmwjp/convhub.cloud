<?=$this->element("page_title")?>
<div class="row mt-2">
    <div class="col-3">
        <nav class="nav nav-pills flex-column">
            <a class="nav-link <?if($section=="yet"){?>active<?}?>" href="/tickets/index/yet">未割当<div class="float-right"><?=nf($ticket_nums["yet"])?></div></a>
            <a class="nav-link <?if($section=="my"){?>active<?}?>" href="/tickets/index/my">あなたの未解決<div class="float-right"><?=nf($ticket_nums["my"])?></div></a>
            <a class="nav-link <?if($section=="not_end"){?>active<?}?>" href="/tickets/index/not_end">すべての未解決<div class="float-right"><?=nf($ticket_nums["not_end"])?></div></a>
            <a class="nav-link <?if($section=="is_end"){?>active<?}?>" href="/tickets/index/is_end">すべての解決済<div class="float-right"><?=nf($ticket_nums["is_end"])?></div></a>
        </nav>
    </div>
    <div class="col-9">
        <ul class="list-group">
            <?foreach($tickets as $ticket){?>
                <a class="list-group-item list-group-item-action" href="/tickets/detail/<?=esc($ticket->id)?>">
                    <div class="float-right">
                        <span class="badge badge-<?=esc($this->model("Tickets")->params["status"][$ticket->status]["color"])?> p-1"><?=esc($this->model("Tickets")->params["status"][$ticket->status]["text"])?></span>
                    </div>
                    <div class="text-muted">
                        <small><?=changeDate($ticket->created)?></small>
                        <?if($ticket->last_comment_user_id != ""){?>
                        <small class="ml-2">更新：<?=changeDate($ticket->last_comment_datetime)?></small>
                        <?}?>
                    </div>
                    <?=esc($ticket->title)?>
                </a>
            <?}?>
        </ul>
    </div>
</div>
