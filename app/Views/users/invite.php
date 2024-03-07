<?=$this->element("page_title")?>
<form method="post" action="/users/invite">
	<div class="alert ">
		招待したいメールアドレスを入力してください。
	</div>

	<div class="form-group">
		<div><label>ニックネーム</label></div>
		<input type="text" name="nickname" class="form-control" value="<?=esc(request()->getPost("nickname"))?>">
		<?=err($errors->getError("nickname"))?>
	</div>
	<div class="form-group">
		<div><label>メールアドレス</label></div>
		<input type="text" name="mail" class="form-control" value="<?=esc(request()->getPost("mail"))?>">
		<?=err($errors->getError("mail"))?>
		<?if(!empty($already_user)){?>
			<div class="badge badge-danger p-1">そのメールアドレスはすでにユーザー登録されています</div>
		<?}?>
	</div>
	<div class="form-group">
		<div><label>権限</label></div>
		<div><input type="checkbox" name="auths[]" value="user" <?if(@in_array("user",(array)request()->getPost("auths"))){?>checked<?}?>>&nbsp;ユーザー管理</div>
		<div><input type="checkbox" name="auths[]" value="form" <?if(@in_array("form",(array)request()->getPost("auths"))){?>checked<?}?>>&nbsp;フォーム管理</div>
		<div><input type="checkbox" name="auths[]" value="widget" <?if(@in_array("widget",(array)request()->getPost("auths"))){?>checked<?}?>>&nbsp;ウィジェット管理</div>
	</div>
	<div class="text-center mt-4"><button type="submit" class="btn btn-dark ">招待メールを送信する</button></div>
	<input type="hidden" name="execute" value="on">
	<?=csrf()?>
</form>
