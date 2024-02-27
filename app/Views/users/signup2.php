<?=$this->element("page_title")?>
<div class="alert">
	OP-NETの会員登録を行います。<br>
	<a href="/statics/term">利用規約</a>の確認をお願いします。<br>
</div>
<div class="alert border" style="height:300px;overflow:scroll;">
	<?=view("/statics/term")?>
</div>
<form action="/users/signup2_end/<?=esc($crypt_user_id)?>" method="post">
	<div class="form-group">
		<div><label>利用規約の同意</label></div>
		<input type="checkbox" value="1" id="term_check" name="term_check">
		<label for="term_check">利用規約を確認し、同意しました</label>
		<?=err($errors->getError("term_check"))?>		
	</div>
	<div class="form-group">
		<label>パスワード</label>
		<input type="password" name="password" class="form-control" value="<?=esc(request()->getPost("password"))?>">
		<?=err($errors->getError("password"))?>
	</div>
	<div class="form-group">
		<label>パスワード再入力</label>
		<input type="password" name="password_confirm" class="form-control" value="<?=esc(request()->getPost("password_confirm"))?>">
		<?=err($errors->getError("password_confirm"))?>
	</div>
	<div class="text-center">
		<button type="submit" class="btn btn-dark" name="execute" value="on">会員登録を完了する</button>
	</div>
	<?=csrf()?>
</form>