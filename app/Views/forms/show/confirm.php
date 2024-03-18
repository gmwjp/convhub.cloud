<div class="row mt-4">
    <div class="col-md-8 col-sm-12">
        <form method="post" action="/forms/show/complate/<?=esc($form->code)?><?if(request()->getGet("subform")){?>?subform=<?=esc(request()->getGet("subform"))?><?}?>">
            <div class="card">
                <div class="card-header">
                    <h4>問い合わせ内容の確認</h4>
                </div>
                <div class="card-body">
                    <?if($items && !empty($items->results)){?>
                        <div class="form-group" id="helps">
                            以下のヘルプ記事で解決できますか？
                            <?foreach($items->results as $key => $result){?>
                            <div class="mt-2">
                                <a href="<?=esc($result->public_url)?>" target="_blank" data-target="read_<?=esc($key)?>" class="fw-bold notion_links"><?=esc($result->properties->title->title[0]->plain_text)?><i class="ml-1 fal fa-external-link"></i></a><br>
                                <small class="text-muted"><?=esc(@$result->detail->results[0]->paragraph->rich_text[0]->plain_text)?></small>
                                <input type="hidden" name="notion_title[]" value="<?=esc($result->properties->title->title[0]->plain_text)?>">
                                <input type="hidden" name="notion_url[]" value="<?=esc($this->library("Crypt2")->encode($result->url))?>">
                                <input type="hidden" name="notion_read[]" value="0" id="read_<?=esc($key)?>">
                            </div>
                            <?}?>
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-secondary mb-1 execute_button" data-value="answer">はい、解決しました</button>
                                <button type="button" class="btn btn-secondary mb-1" id="no_button">いいえ、解決できないので問い合わせを送信します</button>
                            </div>
                        </div>
                    <?}?>
                    <div <?if($items && !empty($items->results)){?>class="none"<?}?> id="form">
                        <div class="form-group">
                            運営事務局に以下の内容で問い合わせを送信してよろしいですか？
                        </div>
                        <?if(request()->getGet("subform")){?>
                            <?foreach($subforms as $subform){?>
                                <?if($subform->id == request()->getGet("subform")){?>
                                    <div class="form-group">
                                        <label>該当の問題</label>
                                        <div><?=esc($subform->name)?></div>
                                    </div>
                                <?}?>
                            <?}?>
                        <?}?>
                        <div class="form-group">
                            <label>メールアドレス</label>
                            <div><?=esc(request()->getPost("mail"))?></div>
                            <input type="hidden" name="mail" value="<?=esc(request()->getPost("mail"))?>">
                        </div>
                        <div class="form-group">
                            <label>件名</label>
                            <div><?=esc(request()->getPost("title"))?></div>
                            <input type="hidden" name="title" value="<?=esc(request()->getPost("title"))?>">
                        </div>
                        <?if($form_items){?>
                            <?foreach($form_items as $item){?>
                                <div class="form-group">
                                    <label><?=esc($item->name)?></label>
                                    <?if($item->section == "textbox"){?>
                                        <div><?=esc(request()->getPost("form_item_".$item->id))?></div>
                                        <input type="hidden" name="form_item_<?=esc($item->id)?>" value="<?=esc(request()->getPost("form_item_".$item->id))?>">
                                    <?}?>
                                    <?if($item->section == "textarea"){?>
                                        <div><?=nl2br(esc(request()->getPost("form_item_".$item->id)))?></div>
                                        <input type="hidden" name="form_item_<?=esc($item->id)?>" value="<?=esc(request()->getPost("form_item_".$item->id))?>">
                                    <?}?>
                                    <?if($item->section == "radio"){?>
                                        <div><?=esc(request()->getPost("form_item_".$item->id))?></div>
                                        <input type="hidden" name="form_item_<?=esc($item->id)?>" value="<?=esc(request()->getPost("form_item_".$item->id))?>">
                                    <?}?>
                                    <?if($item->section == "checkbox"){?>
                                        <?foreach(request()->getPost("form_item_".$item->id) as $body){?>
                                            <div><?=esc($body)?></div>
                                        <?}?>
                                        <input type="hidden" name="form_item_<?=esc($item->id)?>" value="<?=esc(implode("\n",request()->getPost("form_item_".$item->id)))?>">
                                    <?}?>
                                </div>
                            <?}?>
                        <?}?>
                        <?if(request()->getGet("subform")){?>
                            <?foreach($subforms as $subform){?>
                                <?if($subform->id == request()->getGet("subform")){?>
                                    <?if($subform->items){?>
                                        <?foreach($subform->items as $item){?>
                                            <div class="form-group">
                                                <label><?=esc($item->name)?></label>
                                                <?if($item->section == "textbox"){?>
                                                    <div><?=esc(request()->getPost("subform_item_".$item->id))?></div>
                                                    <input type="hidden" name="subform_item_<?=esc($item->id)?>" value="<?=esc(request()->getPost("subform_item_".$item->id))?>">
                                                <?}?>
                                                <?if($item->section == "textarea"){?>
                                                    <div><?=nl2br(esc(request()->getPost("subform_item_".$item->id)))?></div>
                                                    <input type="hidden" name="subform_item_<?=esc($item->id)?>" value="<?=esc(request()->getPost("subform_item_".$item->id))?>">
                                                <?}?>
                                                <?if($item->section == "radio"){?>
                                                    <div><?=esc(request()->getPost("subform_item_".$item->id))?></div>
                                                    <input type="hidden" name="subform_item_<?=esc($item->id)?>" value="<?=esc(request()->getPost("subform_item_".$item->id))?>">
                                                <?}?>
                                                <?if($item->section == "checkbox"){?>
                                                    <?foreach(request()->getPost("subform_item_".$item->id) as $body){?>
                                                        <div><?=esc($body)?></div>
                                                    <?}?>
                                                    <input type="hidden" name="subform_item_<?=esc($item->id)?>" value="<?=esc(implode("\n",request()->getPost("subform_item_".$item->id)))?>">
                                                <?}?>
                                            </div>
                                        <?}?>
                                    <?}?>
                                <?}?>
                            <?}?>
                        <?}?>
                        <div class="form-group">
                            <label>問い合わせ内容</label>
                            <div><?=nl2br(esc(request()->getPost("body")))?></div>
                            <input type="hidden" name="body" value="<?=esc(request()->getPost("body"))?>">
                        </div>
                        <?if(request()->getPost("files")){?>
                            <div class="form-group">
                                <label>添付ファイル</label>
                                <div>
                                    <?foreach (request()->getPost("files") as $key => $attach){ ?>
                                        <div><?=esc($attach["file_name"])?></div>
                                    <?}?>
                                </div>
                            </div>
                       <?}?>
                        <div class="form-group">
                            <div class="text-center">
                                <div class="text-muted my-2"><small>問い合わせを送信すると「<?=esc(env("smtp.from"))?>」から自動返信メールを送信します</small></div>
                                <button type="button" class="btn btn-dark execute_button" data-value="on">問い合わせを送信する</button>
                                <div class="mt-2"><button type="button" class="btn btn-light submit" data-action="/forms/show/input/<?=esc($form->code)?><?if(request()->getGet("subform")){?>?subform=<?=esc(request()->getGet("subform"))?><?}?>">戻る</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?=csrf()?>
            <input type="hidden" name="execute" value="on" id="execute">
        </form>
    </div>
    <div class="col-md-4 col-sm-12">
        <?=$this->element("most_widget")?>
                                    
</div>
</div>
<script>
var refresh = false;
$(function(){
    $("#no_button").click(function(){
        $("#helps").hide();
        $("#form").fadeIn();
    })
    $('.notion_links').click(function(e) {
        var targetId = $(this).data('target');
        $('#' + targetId).val('1');
    });
    $(".execute_button").click(function(e){
        var self = this;
        $.ajax({
            url: '/widgets/get_token',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                csrf_token_value = data.value;
                $('input[name="' + csrf_token_name + '"]').val(csrf_token_value); 
                $("#execute").val($(self).data("value"));
                $("form").submit();
            },
            error: function() {
                console.error('CSRF取得エラー');
            }
        });
    });
});
</script>