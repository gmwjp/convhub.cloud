<?=$this->element("page_title")?>
<?=$this->element("navi/user",array("index"=>"mail"))?>
<form action="/users/edit_mail_exec/<?=esc($crypt_user_id)?>/<?=esc($crypt_mail)?>" method="post">
	<div class="alert">
	メールアドレスを変更します。<br>
	よろしいですか？
	</div>
	<div class="form-group">
		<div><label>現在のメールアドレス</label></div>
		<div><?=esc(mask($my_user->mail))?></div>
	</div>
	<div class="form-group">
		<div><label>新しいメールアドレス</label></div>
		<div><?=esc($uncrypt_mail)?></div>
	</div>
	<div class="text-center mt-4"><button type="submit" class="btn btn-dark ">メールアドレスを変更する</button></div>
	<input type="hidden" name="execute" value="on">
	<?=csrf()?>
</form>
