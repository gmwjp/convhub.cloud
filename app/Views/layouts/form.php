<!DOCTYPE html>
<html lang="ja">
    <head>
		<?=$this->element("analytics")?>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="pinterest" content="nopin" />
		<meta name="description" content="" />
		<?if(!empty($meta)){?>
			<?foreach($meta as $key => $val){?>
			<?foreach($val as $key2 => $val2){?>
				<meta name="<?=$key2?>" content="<?=$val2?>">
			<?}?>
			<?}?>
		<?}?>
		<title><?if(!empty($page_title)){?><?=$page_title?> | <?}?>お問い合わせ</title>
		<link rel="shortcut icon" href="/assets/images/favicon.ico">
		<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
		<link href="/assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
		<link href="/assets/css/notifIt.css" rel="stylesheet" media="screen">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
		<script src="/assets/js/convhub.js"></script>
		<script src="/assets/js/notifIt.min.js"></script>
		<script type="text/javascript" src="https://ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script src="https://widget.univapay.com/client/checkout.js"></script>
		<script>
			var csrf_token_name = '<?=csrf_token()?>';
			var csrf_token_value = '<?=csrf_hash()?>';
			function postData(url, data, done_func=null, fail_func=null){
				$.ajax({
					url: '/users/get_token',
					method: 'GET',
					dataType: 'json',
					success: function(data) {
						csrf_token_value = data.value;
						$('input[name="' + csrf_token_name + '"]').val(csrf_token_value); 
					},
					error: function() {
						console.error('CSRF取得エラー');
					}
				}).done(function(){
					data[csrf_token_name] = csrf_token_value;
					$.post(url,data).done(function(data, textStatus, jqXHR) {
						csrf_token_value = jqXHR.getResponseHeader('X-CSRF-TOKEN');
						$('input[name="' + csrf_token_name + '"]').val(csrf_token_value); 
						if(done_func) done_func(data);
					}).fail(function(data){
						if(fail_func) fail_func(data);
					});
				});
			}
		</script>
		<style>
			.modal-dialog.modal-custom {
				max-width: 800px; /* 例として800pxに設定 */
			}
			.none {
				display:none;
			}
			.item-title {
				font-weight: bold;
				margin-top: 10px;
				margin-bottom: 10px;
			}
			.inline {
				display:inline;
			}
		</style>
    </head>
    <body class="loading">
        <!-- NAVBAR START -->
        <nav class="navbar navbar-expand-lg py-lg-3 navbar-light bg-light">
            <div class="container">
                <!-- logo -->
                <a href="/forms/show/input/<?=esc($form->code)?>" class="navbar-brand mr-lg-5 p-0">
					<?if(file_exists(dirname(__FILE__)."/../../../public/img/forms/".$form->code.".png")){?>
						<img src="/img/forms/<?=esc($form->code)?>.png?d=<?=date("YmdHis")?>">
					<?}?>
                </a>
				<?if($form->url !=""){?>
				<div class="float-right">
					<a href="<?=esc($form->url)?>" class="btn btn-secondary btn-sm">サイトに戻る<i class="fal fa-arrow-from-left ml-1"></i></a>
				</div>
				<?}?>
            </div>
        </nav>
        <!-- NAVBAR END -->
		<div class="clearfix pb-5">
			<div class="container pb-5">
	        <?=$content?>
			</div>
		</div>
        <script src="/assets/js/vendor.min.js"></script>
        <script src="/assets/js/app.min.js"></script>
    </body>
</html>
<script>
<?if(session()->getFlashdata('message')){?>
	info("<?=session()->getFlashdata('message')?>");
<?}?>
<?if(session()->getFlashdata('success')){?>
	success("<?=session()->getFlashdata('success')?>");
<?}?>
<?if(session()->getFlashdata('error')){?>
	error("<?=session()->getFlashdata('error')?>");
<?}?>
</script>
