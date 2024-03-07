<?=$this->element("page_title")?>
<?=$this->element("navi/user",array("index"=>"edit"))?>
<form method="post" action="/users/edit">
	<div class="alert ">
		プロフィール情報を編集します。
	</div>
	<div class="form-group">
		<div><label>ニックネーム</label></div>
		<input type="text" name="nickname" class="form-control" value="<?=esc(request()->getPost("nickname"))?>">
		<?=err($errors->getError("nickname"))?>
	</div>
	<div class="text-center mt-4"><button type="submit" class="btn btn-dark ">プロフィールを編集する</button></div>
	<input type="hidden" name="execute" value="on">
	<?=csrf()?>
</form>
