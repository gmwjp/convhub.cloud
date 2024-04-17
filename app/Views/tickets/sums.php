<?=$this->element("page_title")?>
<nav class="nav nav-pills mb-2">
    <a class="nav-link <?if($section=="user"){?>active<?}?>" href="/tickets/sums/user">ユーザー</a>
    <a class="nav-link <?if($section=="group"){?>active<?}?>" href="/tickets/sums/group">グループ</a>
    <a class="nav-link <?if($section=="form" || $section=="subform"){?>active<?}?> " href="/tickets/sums/form">フォーム
    <?if($section == "subform" && request()->getGet("form_id")){?>
        <?foreach($forms as $form){?>
            <?if($form->id == request()->getGet("form_id")){?>
                ：<?=esc($form->name)?>
            <?}?>
        <?}?>
    <?}?>
    </a>
</nav>
<div class="row">
    <div class="col text-left"><a href="/tickets/sums/<?=esc($section)?>/<?=esc($prev_month)?><?if(request()->getGet("form_id")){?>?form_id=<?=request()->getGet("form_id")?><?}?>" class="btn btn-light"><span class="fal fa-chevron-left mr-1"></span>前月</a></div>
    <div class="col text-center"><a href="/tickets/sums/<?=esc($section)?>/<?=date("Y-m")?><?if(request()->getGet("form_id")){?>?form_id=<?=request()->getGet("form_id")?><?}?>" class="btn btn-light">当月</a></div>
    <div class="col text-right"><a href="/tickets/sums/<?=esc($section)?>/<?=esc($next_month)?><?if(request()->getGet("form_id")){?>?form_id=<?=request()->getGet("form_id")?><?}?>" class="btn btn-light">次月<span class="fal fa-chevron-right ml-1"></span></a></div>
</div>
<table class="table table-sm mt-2 table-bordered" style="table-layout: fixed;">
    <tr class="bg-secondary text-white">
        <td>日付</td>
        <?if($section == "user"){?>
            <?foreach($users as $user){?>
                <td><?=esc($user->nickname)?></td>
                <?$total[$user->id] = 0?>
            <?}?>
        <?}?>
        <?if($section == "group"){?>
            <?foreach($groups as $group){?>
                <td><?=esc($group->name)?></td>
                <?$total[$group->id] = 0?>
            <?}?>
        <?}?>
        <?if($section == "form"){?>
            <?foreach($forms as $form){?>
                <td>
                    <?=esc($form->name)?>
                    <a href="/tickets/sums/subform?form_id=<?=esc($form->id)?>" class="ml-1" data-toggle="tooltip" data-placement="top" title="サブフォームごと"><span class="fal fa-list"></span></a>
                </td>
                <?$total[$form->id] = 0?>
            <?}?>
        <?}?>
        <?if($section == "subform"){?>
            <?foreach($subforms as $subform){?>
                <?if(request()->getGet("form_id") == $subform->form_id){?>
                    <td><?=esc($subform->name)?></td>
                    <?$total[$subform->id] = 0?>
                <?}?>
            <?}?>
        <?}?>
        <td>小計</td>
    </tr>
<?for($i = 0 ; $i < date("t",strtotime($param["start_date"]));$i++){?>
    <tr>
        <td><?=esc(date("Y-m-d",strtotime($param["start_date"]." +{$i} days")))?></td>
        <?$sum = 0;?>
        <?if($section == "user"){?>
            <?foreach($users as $user){?>
                <? $count = 0;?>
                <?foreach($tickets as $ticket){?>
                    <?if(date("Y-m-d",strtotime($ticket->created)) == date("Y-m-d",strtotime($param["start_date"]." +{$i} days"))){?>
                        <?if($user->id == $ticket->user_id){?>
                            <?$count++?>
                            <?$total[$user->id]++?>
                        <?}?>
                    <?}?>
                <?}?>
                <td><?=nf($count)?></td>
                <?$sum += $count;?>
            <?}?>
        <?}?>
        <?if($section == "group"){?>
            <?foreach($groups as $group){?>
                <? $count = 0;?>
                <?foreach($tickets as $ticket){?>
                    <?if(date("Y-m-d",strtotime($ticket->created)) == date("Y-m-d",strtotime($param["start_date"]." +{$i} days"))){?>
                        <?if($group->id == $ticket->group_id){?>
                            <?$count++?>
                            <?$total[$group->id]++?>
                        <?}?>
                    <?}?>
                <?}?>
                <td><?=nf($count)?></td>
                <?$sum += $count;?>
            <?}?>
        <?}?>
        <?if($section == "form"){?>
            <?foreach($forms as $form){?>
                <? $count = 0;?>
                <?foreach($tickets as $ticket){?>
                    <?if(date("Y-m-d",strtotime($ticket->created)) == date("Y-m-d",strtotime($param["start_date"]." +{$i} days"))){?>
                        <?if($form->id == $ticket->form_id){?>
                            <?$count++?>
                            <?$total[$form->id]++?>
                        <?}?>
                    <?}?>
                <?}?>
                <td><?=nf($count)?></td>
                <?$sum += $count;?>
            <?}?>
        <?}?>
        <?if($section == "subform"){?>
            <?foreach($subforms as $subform){?>
                <?if(request()->getGet("form_id") == $subform->form_id){?>
                    <? $count = 0;?>
                    <?foreach($tickets as $ticket){?>
                        <?if(date("Y-m-d",strtotime($ticket->created)) == date("Y-m-d",strtotime($param["start_date"]." +{$i} days"))){?>
                            <?if($subform->id == $ticket->subform_id){?>
                                
                                <?$count++?>
                                <?$total[$subform->id]++?>
                                
                            <?}?>
                        <?}?>
                    <?}?>
                    <td><?=nf($count)?></td>
                    <?$sum += $count;?>
                <?}?>
            <?}?>
        <?}?>
        <td><?=nf($sum)?></td>
    </tr>
<?}?>
    <tr>
        <?$sum_total = 0;?>
        <th>合計</th>

        <?if($section == "user"){?>
            <?foreach($users as $user){?>
                <th><?=nf($total[$user->id])?></th>
                <?$sum_total+=$total[$user->id]?>
            <?}?>
        <?}?>
        <?if($section == "group"){?>
            <?foreach($groups as $group){?>
                <th><?=nf($total[$group->id])?></th>
                <?$sum_total+=$total[$group->id]?>
            <?}?>
        <?}?>
        <?if($section == "form"){?>
            <?foreach($forms as $form){?>
                <th><?=nf($total[$form->id])?></th>
                <?$sum_total+=$total[$form->id]?>
            <?}?>
        <?}?>
        <?if($section == "subform"){?>
            <?foreach($subforms as $subform){?>
                <?if(request()->getGet("form_id") == $subform->form_id){?>
                <th><?=nf($total[$subform->id])?></th>
                <?$sum_total+=$total[$subform->id]?>
                <?}?>
            <?}?>
        <?}?>
        <th><?=nf($sum_total)?></th>
    </tr>
</table>