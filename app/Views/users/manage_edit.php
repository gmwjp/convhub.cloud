<?=$this->element("page_title")?>
<form method="post" action="/users/manage_edit/<?=esc($user->id)?>">
<div class="form-group">
		<div><label>ニックネーム</label></div>
		<input type="text" name="nickname" class="form-control" value="<?=esc(request()->getPost("nickname"))?>">
		<?=err($errors->getError("nickname"))?>
	</div>
	<div class="form-group">
		<div><label>メールアドレス</label></div>
		<input type="text" name="mail" class="form-control" value="<?=esc(request()->getPost("mail"))?>">
		<?=err($errors->getError("mail"))?>
	</div>
	<div class="form-group">
		<div><label>グループ</label></div>
		<select name="group_id" class="form-control custom-select">
			<option value="0"></option>
			<?if($groups){?>
			<?foreach($groups as $group){?>
				<option value="<?=esc($group->id)?>" <?if($group->id == request()->getPost("group_id")){?>selected<?}?>><?=esc($group->name)?></option>
			<?}?>
			<?}?>
		</select>
		<?=err($errors->getError("group_id"))?>
	</div>
	<div class="form-group">
		<div><label>権限</label></div>
		<div><input type="checkbox" name="auths[]" value="user" <?if(in_array("user",request()->getPost("auths"))){?>checked<?}?>>&nbsp;ユーザー管理</div>
		<div><input type="checkbox" name="auths[]" value="form" <?if(in_array("form",request()->getPost("auths"))){?>checked<?}?>>&nbsp;フォーム管理</div>
		<div><input type="checkbox" name="auths[]" value="widget" <?if(in_array("widget",request()->getPost("auths"))){?>checked<?}?>>&nbsp;ウィジェット管理</div>
	</div>
	<div class="text-center mt-4"><button type="submit" class="btn btn-dark ">保存する</button></div>
	<input type="hidden" name="execute" value="on">
	<?=csrf()?>
</form>
