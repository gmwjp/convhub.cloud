<div class="form-group">
    <div class="item-title">
        サブフォーム名
    </div>
    <div>
        <input type="text" name="name" class="form-control" value="<?=esc(request()->getPost("name"))?>">
        <?=err($errors->getError("name"))?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        サブフォーム説明文
    </div>
    <div>
        <textarea name="body" class="form-control" rows="4"><?=esc(request()->getPost("body"))?></textarea>
        <?=err($errors->getError("body"))?>
    </div>
</div>
<div class="form-group">
    <div class="item-title">
        初期担当者
    </div>
    <div>
        <select name="init_user_id" class="form-control custom-select">
            <option value=""></option>
            <?foreach($users as $user){?>
                <option value="<?=esc($user->id)?>" <?if(request()->getPost("init_user_id")==$user->id){?>selected<?}?>><?=esc($user->nickname)?></option>
            <?}?>
        </select>
        <?=err($errors->getError("init_user_id"))?>
    </div>
</div>

<div class="form-group">
    <input type="hidden" name="copy_flg" value="0">
    <input type="checkbox" name="copy_flg" value="1" id="copy_flg" <?if(request()->getPost("copy_flg") == 1){?>checked<?}?>>
    <label for="copy_flg">問い合わせフォームにて、サブフォーム名を件名にコピーする</label>
</div>

<div class="form-group">
    <div class="item-title">
        設問項目
    </div>
    <div>
        <div id="items">
            <?if(request()->getPost("names")){?>
                <?foreach(request()->getPost("names") as $key => $val){?>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="item-title">
                                    項目名
                                </div>
                                <div>
                                    <input type="text" name="names[]" class="form-control" value="<?=esc(request()->getPost("names")[$key])?>">
                                    <?=err($errors->getError("names_".$key))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="item-title">
                                    説明文
                                </div>
                                <div>
                                    <input type="text" name="abouts[]" class="form-control" value="<?=esc(request()->getPost("abouts")[$key])?>">
                                    <?=err($errors->getError("abouts_".$key))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="item-title">
                                    入力形式
                                </div>
                                <div>
                                    <select class="form-control custom-select select" name="select[]">
                                        <option value="textbox"  <?if(request()->getPost("select")[$key] == "textbox"){?>selected<?}?>>１行テキスト</option>
                                        <option value="textarea" <?if(request()->getPost("select")[$key] == "textarea"){?>selected<?}?>>複数行テキスト</option>
                                        <option value="radio"    <?if(request()->getPost("select")[$key] == "radio"){?>selected<?}?>>単一選択肢</option>
                                        <option value="checkbox" <?if(request()->getPost("select")[$key] == "checkbox"){?>selected<?}?>>複数選択肢</option>
                                    </select>
                                    <?=err($errors->getError("select_".$key))?>
                                    <div class="mt-2 bodies <?if(request()->getPost("select")[$key] == "textbox" || request()->getPost("select")[$key] == "textarea"){?>none<?}?>">
                                        <textarea class="form-control" rows="4" placeholder="選択肢を改行区切りで入力" name="bodies[]"><?if(!empty(request()->getPost("bodies")[$key])){?><?=esc(request()->getPost("bodies")[$key])?><?}?></textarea>
                                        <?=err($errors->getError("bodies_".$key))?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix mt-2">
                                <div class="float-left">
                                    <input type="checkbox" name="required[]" value="1" <?if(!empty(request()->getPost("required")[$key]) && request()->getPost("required")[$key] == "1"){?>checked<?}?>>&nbsp;必須入力
                                </div>
                                <div class="float-right">
                                    <button type="button" class="btn btn-sm btn-light up_button">上へ</button>
                                    <button type="button" class="btn btn-sm btn-light down_button">下へ</button>
                                    <button type="button" class="btn btn-sm btn-light del_button">削除</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?}?>
            <?}?>
        </div>
        <button type="button" class="btn btn-secondary" id="add_button">設問を追加する</button>
    </div>
</div>
<script>
$(function(){
    $("#add_button").click(function(){
        $("#items").append($("#template").html());
    });
    $("body").on("change",".select",function(){
        if($(this).val() == "textbox"){
            $(this).next(".bodies").hide();
        }
        if($(this).val() == "textarea"){
            $(this).next(".bodies").hide();
        }
        if($(this).val() == "radio"){
            $(this).next(".bodies").show();
        }
        if($(this).val() == "checkbox"){
            $(this).next(".bodies").show();
        }
    });
    $("body").on("click",".up_button",function(){
        var current = $(this).closest('.card');
        var previous = current.prev('.card');
        if(previous.length !== 0){
            current.hide(function(){
                current.insertBefore(previous).show('slow');
            });
        }
    });
    $("body").on("click",".down_button",function(){
        var current = $(this).closest('.card');
        var next = current.next('.card');
        if(next.length !== 0){
            current.hide(function(){
                current.insertAfter(next).show('slow');
            });
        }
    });
    $("body").on("click",".del_button",function(){
        if(confirm("この項目を削除してよろしいですか？")){
            $(this).closest('.card').hide('slow', function(){
                $(this).remove();
            });
        }
    });
});
</script>