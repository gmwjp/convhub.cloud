<?=$this->element("page_title")?>
<div class="alert">
    ウィジェットを作成し、Notionの記事に設置します。<br>
    ウィジェットが設置されているNotionページが削除されている場合はウィジェットも削除します。
</div>
<form method="post" action="/widgets/sync_data/<?=esc($section)?>">
    <div class="form-group">
        <div class="item-title">
            対象の問い合わせフォーム
        </div>
        <div>
            <select name="form_id" class="form-control custom-select">
                <?foreach($forms as $form){?>
                    <option value="<?=esc($form->id)?>" <?if(request()->getPost("form_id") == $form->id){?>selected<?}?>><?=esc($form->name)?></option>
                <?}?>
            </select>
            <?=err($errors->getError("form_id"))?>
            <div class="text-muted"><small>指定した問い合わせフォームに紐づいているNotionページとその配下ページ用のウィジェットを作成します</small></div>
        </div>
    </div>
    <div class="text-center">
        <div class="mt-2">
            <button type="submit" class="btn btn-dark" name="execute" value="on">同期を実行</button>
        </div>
        <div class="mt-2">
            <a href="/widgets/index/<?=esc($section)?>" class="btn btn-link">一覧に戻る</a>
        </div>
    </div>
    <?=csrf()?>
</form>
<?=$this->element("page_title",["title"=>"除外URL"])?>
<?if($ignores){?>
    <?foreach($ignores as $ignore){?>
        <div class="mt-1"><a href="<?=esc($ignore->url)?>" target="_blank"><?=esc($ignore->url)?><i class="ml-1 fal fa-external-link"></i></a></div>
    <?}?>
<?}?>