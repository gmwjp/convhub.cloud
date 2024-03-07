<?=$this->element("page_title")?>
<div class="form-group">
	<div><label>ニックネーム</label></div>
	<div><?=esc($user->nickname)?></div>
</div>
<div class="form-group">
	<div><label>メールアドレス</label></div>
	<div><?=esc(mask($user->mail))?></div>
</div>
<div class="form-group">
	<div><label>権限</label></div>
	<div>
		<?if($user->auths !=""){?>
		<div><?if(in_array("user",explode(",",$user->auths))){?>ユーザー管理<?}?></div>
		<div><?if(in_array("form",explode(",",$user->auths))){?>フォーム管理<?}?></div>
		<div><?if(in_array("widget",explode(",",$user->auths))){?>ウィジェット管理<?}?></div>
		<?}?>
	</div>
</div>
<div class="text-center">
    <div class="mt-2">
        <button type="button" data-action="/users/manage_edit/<?=esc($user->id)?>" class="btn btn-outline-secondary href">編集する</button>
        <button type="button" data-confirm="このデータを削除してよろしいですか？" data-action="/users/del/<?=esc($user->id)?>" class="btn btn-outline-danger href">削除する</button>
    </div>
    <div class="mt-2">
        <a href="/teams/index" class="btn btn-link">一覧に戻る</a>
    </div>
</div>
