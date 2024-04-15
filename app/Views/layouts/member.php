<!DOCTYPE html>
<html lang="ja">
    <head>
		<?=$this->element("analytics")?>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="pinterest" content="nopin" />
		<meta name="description" content="ConvHUB" />
		<?if(!empty($meta)){?>
			<?foreach($meta as $key => $val){?>
			<?foreach($val as $key2 => $val2){?>
				<meta name="<?=$key2?>" content="<?=$val2?>">
			<?}?>
			<?}?>
		<?}?>
		<title><?if(!empty($page_title)){?><?=$page_title?> | <?}?>ConvHUB</title>
		<link rel="shortcut icon" href="/assets/images/favicon.ico?d=20240415">
		<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
		<link href="/assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
		<link href="/assets/css/notifIt.css" rel="stylesheet" media="screen">
		<link href="/assets/css/pictsquare.css" rel="stylesheet" medit="screen">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
		<script <?=csp_script_nonce_test()?> src="/assets/js/convhub.js?d=20200510_6"></script>
		<script <?=csp_script_nonce_test()?> src="/assets/js/notifIt.min.js"></script>
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
    </head>
    <body class="loading">
        <!-- NAVBAR START -->
        <nav class="navbar navbar-expand-lg py-lg-3 navbar-dark bg-dark">
            <div class="container">
                <!-- logo -->
                <a href="/" class="navbar-brand mr-lg-5">
                    ConvHUB
                </a>
				<?php
				/*
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="mdi mdi-menu"></i>
                </button>

                <!-- menus -->
                <div class="collapse navbar-collapse" id="navbarNavDropdown">

                    <!-- left menu -->
                    <ul class="navbar-nav mr-auto align-items-center">
						<li class="nav-item mx-lg-1">
                            <a class="nav-link" href="/statics/howto">申込方法</a>
                        </li>
                        <li class="nav-item mx-lg-1">
                            <a class="nav-link" href="/statics/price">料金</a>
                        </li>
						<li class="nav-item mx-lg-1">
                            <a class="nav-link" href="https://support.g-m-w.jp/products/qas/pictsquare" target="_blank">FAQ</a>
                        </li>
                    </ul>
                </div>
				*/
				?>
            </div>
        </nav>
        <!-- NAVBAR END -->
		<div class="clearfix pb-5">
			<div class="container pb-5">
	        <?=$content?>
			</div>
		</div>

		<footer class="footer" style="left:0px;">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<?=date("Y")?> © <a href="/">ConvHUB</a>
					</div>
					<div class="col-md-9">
						<div class="text-md-right footer-links d-none d-md-block">
							<small>
							<a href="/statics/term">利用規約</a>
							<a href="/statics/policy">プライバイシーポリシー</a>
							<a href="/statics/toku">特定商取引法に関する表記</a>
							<a href="/statics/company">運営会社</a>
						</small>
						</div>
					</div>
				</div>
			</div>
		</footer>
        <script src="/assets/js/vendor.min.js"></script>
        <script src="/assets/js/app.min.js"></script>
    </body>
</html>
<script <?=csp_script_nonce_test()?>>
<?if(session()->getFlashdata('message')){?>
	info("<?=session()->getFlashdata('message')?>");
<?}?>
<?if(session()->getFlashdata('success')){?>
	success("<?=session()->getFlashdata('success')?>");
<?}?>
<?if(session()->getFlashdata('error')){?>
	if("<?=session()->getFlashdata('error')?>" == "The action you requested is not allowed."){
		error("画面の有効期限が切れたか、再読み込みなどの許可されない操作が行われました。<br>大変お手数ですが再度実行してください。");
	} else {
		error("<?=esc(session()->getFlashdata('error'))?>");
	}
<?}?>
</script>
