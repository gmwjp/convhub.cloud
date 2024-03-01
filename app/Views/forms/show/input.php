<div class="row mt-4">
    <div class="col-md-8 col-sm-12">
        <form method="post" action="/forms/show/confirm/<?=esc($form->code)?><?if(request()->getGet("subform")){?>?subform=<?=esc(request()->getGet("subform"))?><?}?>" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <h4>問い合わせを行う</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <?=nl2br(esc($form->body))?>
                        <?if($subforms){?>
                            <div class="mt-3">以下の中から該当する問題を選択してください</div>
                            <select class="custom-select form-control" id="select_subform">
                                <option value=""></option>
                                <?foreach($subforms as $subform){?>
                                    <option value="<?=esc($subform->id)?>" <?if(request()->getGet("subform") == $subform->id){?>selected<?}?>><?=esc($subform->name)?></option>
                                <?}?>
                            </select>
                            <?foreach($subforms as $subform){?>
                                <?if(request()->getGet("subform") == $subform->id){?>
                                    <div class="mt-3"><?=nl2br(esc($subform->body))?></div>
                                <?}?>
                            <?}?>
                        <?}?>
                    </div>
                    <div id="input_form" class="<?if($subforms && !request()->getGet("subform")){?>none<?}?>">
                        <div class="form-group">
                            <label>メールアドレス&nbsp;<span class="badge badge-info font-weight-normal">必須</span></label>
                            <input type="text" name="mail" class="form-control" value="<?=esc(request()->getPost("mail"))?>">
                            <?=err($errors->getError("mail"))?>
                        </div>
                        <div class="form-group">
                            <label>件名&nbsp;<span class="badge badge-info font-weight-normal">必須</span></label>
                            <input type="text" name="title" class="form-control" value="<?=esc(request()->getPost("title"))?>">
                            <?=err($errors->getError("title"))?>
                        </div>
                        <?if($form_items){?>
                            <?foreach($form_items as $item){?>
                                <div class="form-group">
                                    <label>
                                        <?=esc($item->name)?>
                                        <?if($item->required == 1){?>
                                            &nbsp;<span class="badge badge-info font-weight-normal">必須</span>
                                        <?} else {?>
                                            &nbsp;<span class="badge badge-light font-weight-normal">任意</span>
                                        <?}?>
                                    </label>
                                    <?if($item->section == "textbox"){?>
                                        <input type="text" name="form_item_<?=esc($item->id)?>" class="form-control" value="<?=esc(request()->getPost("form_item_".esc($item->id)))?>" maxlength="255">
                                    <?}?>
                                    <?if($item->section == "textarea"){?>
                                        <textarea name="form_item_<?=esc($item->id)?>" class="form-control"  maxlength="10000"><?=esc(request()->getPost("form_item_".esc($item->id)))?></textarea>
                                    <?}?>
                                    <?if($item->section == "radio"){?>
                                        <?foreach(explode("\n",$item->body) as $key => $body){?>
                                            <?if(trim($body) != ""){?>
                                                <?$body = trim($body)?>
                                                <div><input type="radio" name="form_item_<?=esc($item->id)?>" value="<?=esc($body)?>" id="form_item_<?=esc($item->id)?>_<?=esc($key)?>" <?if(request()->getPost("form_item_".esc($item->id)) == $body){?>checked<?}?>>&nbsp;<label for="form_item_<?=esc($item->id)?>_<?=esc($key)?>"><?=esc($body)?></label></div>
                                            <?}?>
                                        <?}?>
                                    <?}?>
                                    <?if($item->section == "checkbox"){?>
                                        <?foreach(explode("\n",$item->body) as $key => $body){?>
                                            <?if(trim($body) != ""){?>
                                                <?$body = trim($body)?>
                                                <?
                                                $checked = false;
                                                if(request()->getPost("form_item_".$item->id)){
                                                    foreach(request()->getPost("form_item_".$item->id) as $val){
                                                        if($val == $body){
                                                            $checked = true;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <div><input type="checkbox" name="form_item_<?=esc($item->id)?>[]" value="<?=esc($body)?>" id="form_item_<?=esc($item->id)?>_<?=esc($key)?>" <?if($checked){?>checked<?}?>>&nbsp;<label for="form_item_<?=esc($item->id)?>_<?=esc($key)?>"><?=esc($body)?></label></div>
                                            <?}?>
                                        <?}?>
                                    <?}?>
                                    <?=err($errors->getError("form_item_".esc($item->id)))?>
                                    <div class="text-muted mt-1"><small><?=esc($item->about)?></small></div>
                                </div>
                            <?}?>
                        <?}?>
                        <?foreach($subforms as $subform){?>
                            <?if($subform->id == request()->getGet("subform")){?>
                                <?if($subform->items){?>
                                    <?foreach($subform->items as $item){?>
                                        <div class="form-group">
                                            <label>
                                                <?=esc($item->name)?>
                                                <?if($item->required == 1){?>
                                                    &nbsp;<span class="badge badge-info font-weight-normal">必須</span>
                                                <?} else {?>
                                                    &nbsp;<span class="badge badge-light font-weight-normal">任意</span>
                                                <?}?>
                                            </label>
                                            <?if($item->section == "textbox"){?>
                                                <input type="text" name="subform_item_<?=esc($item->id)?>" class="form-control" value="<?=esc(request()->getPost("subform_item_".esc($item->id)))?>" maxlength="255">
                                            <?}?>
                                            <?if($item->section == "textarea"){?>
                                                <textarea name="subform_item_<?=esc($item->id)?>" class="form-control" maxlength="10000"><?=esc(request()->getPost("subform_item_".esc($item->id)))?></textarea>
                                            <?}?>
                                            <?if($item->section == "radio"){?>
                                                <?foreach(explode("\n",$item->body) as $key => $body){?>
                                                    <?if(trim($body) != ""){?>
                                                        <?$body = trim($body)?>
                                                        <div><input type="radio" name="subform_item_<?=esc($item->id)?>" value="<?=esc($body)?>" id="form_item_<?=esc($item->id)?>_<?=esc($key)?>" <?if(request()->getPost("form_item_".esc($item->id)) == $body){?>checked<?}?>>&nbsp;<label for="form_item_<?=esc($item->id)?>_<?=esc($key)?>"><?=esc($body)?></label></div>
                                                    <?}?>
                                                <?}?>
                                            <?}?>
                                            <?if($item->section == "checkbox"){?>
                                                <?foreach(explode("\n",$item->body) as $key => $body){?>
                                                    <?if(trim($body) != ""){?>
                                                        <?$body = trim($body)?>
                                                        <?
                                                        $checked = false;
                                                        if(request()->getPost("form_item_".$item->id)){
                                                            foreach(request()->getPost("form_item_".$item->id) as $val){
                                                                if($val == $body){
                                                                    $checked = true;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <div><input type="checkbox" name="subform_item_<?=esc($item->id)?>[]"  value="<?=esc($body)?>"  id="form_item_<?=esc($item->id)?>_<?=esc($key)?>" <?if($checked){?>checked<?}?>>&nbsp;<label for="form_item_<?=esc($item->id)?>_<?=esc($key)?>"><?=esc($body)?></label></div>
                                                    <?}?>
                                                <?}?>
                                            <?}?>
                                            <?=err($errors->getError("subform_item_".esc($item->id)))?>
                                            <div class="text-muted mt-1"><small><?=esc($item->about)?></small></div>
                                        </div>
                                    <?}?>
                                <?}?>
                            <?}?>
                        <?}?>
                        <div class="form-group">
                            <label>問い合わせ内容&nbsp;<span class="badge badge-info font-weight-normal">必須</span></label>
                            <textarea rows="4" class="form-control" name="body"><?=esc(request()->getPost("body"))?></textarea>
                            <?=err($errors->getError("body"))?>
                            <div class="text-muted mt-1"><small>問い合わせ内容の詳細を入力してください。サポートスタッフのメンバーができるだけ早く対応いたします。</small></div>
                        </div>
                        <div class="form-group">
                            <label>添付ファイル&nbsp;<span class="badge badge-light font-weight-normal">任意</span></label>
                            <div><input type="file" name="files[]" multiple></div>
                            <?=err($errors->getError("files"))?>
                            <div class="text-muted mt-1"><small>複数ファイルを選択できます</small></div>
                        </div>
                        <div class="form-group">
                            <div class="text-center"><button type="submit" class="btn btn-dark" name="execute" value="on">確認画面へ</button></div>
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
    $("#select_subform").change(function(){
        location.href = "/forms/show/input/<?=esc($form->code)?>?subform="+$(this).val();
    });
});
</script>