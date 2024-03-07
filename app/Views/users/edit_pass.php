<?=$this->element("page_title")?>
<?=$this->element("navi/user",array("index"=>"pass"))?>
<form method="post" action="/users/edit_pass">
	<div class="alert ">
		パスワードを編集します。<br>
		次回ログイン時より、新しいパスワードが適用されますのでご注意下さい。
	</div>
	<div class="form-group">
		<div><label>新しいパスワード</label></div>
		<input type="password" name="password" class="form-control" value="<?=esc(request()->getPost("password"))?>">
		<?=err($errors->getError("password"))?>
	</div>
	<div class="form-group">
		<div><label>新しいパスワード：再入力</label></div>
		<input type="password" name="password_confirm" class="form-control" value="<?=esc(request()->getPost("password_confirm"))?>">
		<?=err($errors->getError("password_confirm"))?>
	</div>
	<div class="text-center mt-4"><button type="submit" class="btn btn-dark ">パスワードを変更する</button></div>
	<input type="hidden" name="execute" value="on">
	<?=csrf()?>
</form>
