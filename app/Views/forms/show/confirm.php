<div class="row mt-4">
    <div class="col-md-8 col-sm-12">
        <form method="post" action="/forms/show/complate/<?=esc($form->code)?><?if(request()->getGet("subform")){?>?subform=<?=esc(request()->getGet("subform"))?><?}?>">
            <div class="card">
                <div class="card-header">
                    <h4>問い合わせ内容の確認</h4>
                </div>
                <div class="card-body">
                    <?if($items->results){?>
                        <div class="form-group" id="helps">
                            以下のヘルプ記事で解決できますか？
                            <?foreach($items->results as $result){?>
                            <div class="mt-2">
                                <a href="<?=esc($result->public_url)?>" target="_blank" class="fw-bold"><?=esc($result->properties->title->title[0]->plain_text)?></a><br>
                                <small class="text-muted"><?=esc(@$result->detail->results[0]->paragraph->rich_text[0]->plain_text)?></small>
                            </div>
                            <?}?>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-secondary">はい、解決しました</button>
                                <button type="button" class="btn btn-secondary" id="no_button">解決できないので問い合わせを送信します</button>
                            </div>
                        </div>
                    <?}?>
                    <div <?if($items->results){?>class="none"<?}?> id="form">
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
                        <?if($attaches){?>
                            <div class="form-group">
                                <label>添付ファイル</label>
                                <div>
                                    <?foreach ($attaches as $attach){ ?>
                                        <?//ひとまず表示はファイル名のみにしております?>
                                        <?=$attach["fname"]?><input type="hidden" name="files[]" value="<?=$attach["path"]?>"><br>
                                    <?}?>
                                </div>
                            </div>
                       <?}?>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-dark" name="execute" value="on">送信する</button>
                                <div class="mt-2"><button type="button" class="btn btn-light submit" data-action="/forms/show/input/<?=esc($form->code)?><?if(request()->getGet("subform")){?>?subform=<?=esc(request()->getGet("subform"))?><?}?>">戻る</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?=csrf()?>
        </form>
    </div>
    <div class="col-md-4 col-sm-12">
        <?=nl2br($form->contents_body)?>
    </div>
</div>
<script>
$(function(){
    $("#no_button").click(function(){
        $("#helps").hide();
        $("#form").fadeIn();
    })
});
</script>