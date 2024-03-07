<?=$this->element("page_title")?>
<?=$this->element("navi/user",array("index"=>"mail"))?>
<form method="post" action="/users/edit_mail">
	<div class="alert ">
		メールアドレスを編集します。<br>
		新しいメールアドレスを入力して下さい。<br>
		入力されたメールアドレスに認証用のメールを送信します。<br>
		メール内に記載されているURLをクリックするとメールアドレスの変更が完了します。
	</div>
	<div class="form-group">
		<div><label>現在のメールアドレス</label></div>
		<div><?=esc(mask($my_user->mail))?></div>
	</div>
	<div class="form-group">
		<div><label>新しいメールアドレス</label></div>
		<input type="text" name="mail" class="form-control" value="<?=esc(request()->getPost("mail"))?>">
		<?=err($errors->getError("mail"))?>
		<?if(!empty($already_user)){?><div class="badge badge-danger  p-1 mt-1">すでに使用されているメールアドレスです</div><?}?>
	</div>
	<div class="text-center mt-4"><button type="submit" class="btn btn-dark ">認証メールを送信する</button></div>
	<input type="hidden" name="execute" value="on">
	<?=csrf()?>
</form>
