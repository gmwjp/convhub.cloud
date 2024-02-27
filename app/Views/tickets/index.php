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