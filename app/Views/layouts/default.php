<!DOCTYPE html>
<html lang="ja">
	<head>
		<?=$this->element("analytics")?>
		<meta charset="UTF-8">
		<meta name="facebook-domain-verification" content="evz23atrugsbqrfg3q0gxluz58e5pv" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="pinterest" content="nopin" />
		<meta name="description" content="" />
		
		<title><?if(!empty($page_title)){?><?=esc($page_title)?> | <?}?>ConvHUB</title>
		<link rel="shortcut icon" href="/assets/images/favicon.ico?d=20240415">
		<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
		<link href="/assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
		<link href="/assets/css/notifIt.css" rel="stylesheet" media="screen">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="/assets/css/common.css?d=20230519_1" />
		<script <?=csp_script_nonce_test()?> src="/assets/js/convhub.js?d=20200510_6"></script>
		<script <?=csp_script_nonce_test()?> src="/assets/js/notifIt.min.js"></script>
		<script <?=csp_script_nonce_test()?>>
			var csrf_token_name = '<?=csrf_token()?>';
			var csrf_token_value = '<?=csrf_hash()?>';
			function postData(url,data,done_func=null,fail_func=null){
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
			function ajaxComm(url,method,data,beforesend=null,done=null,fail=null) {
				data[csrf_token_name] = csrf_token_value;
				var jqxhrObj = $.ajax({
					url:url,
					type:method,
					data:data,
					dataType:'json',
					beforesend: beforesend
				}).done(function(data, textStatus, jqXHR) {
					csrf_token_value = jqXHR.getResponseHeader('X-CSRF-TOKEN');
					if(csrf_token_value != null){
						$('input[name="' + csrf_token_name + '"]').val(csrf_token_value); 
					} else {
						console.error("csrf token not found");
					}
					if(done) done(data);
				}).fail(function(XMLHttpRequest, status, e){
					//console.log(e);
					if(fail) fail(XMLHttpRequest, status, e);
				});
				return jqxhrObj;
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
	<body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":true, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": false}'>
		<div class="follow-regist active" id="order-regist" style="line-height:0;background:rgba(0, 0, 0, 0.3);bottom: 0;"></div>
        <div class="wrapper">
			<?if(!empty($my_user)){?>
				<div class="left-side-menu">
					<a href="/" class="logo text-center logo-light">
						<span class="logo-lg">
							<img src="/assets/images/logo-beta/other/logo-pictSQUARE-beta-white.png" alt="" height="30">
						</span>
						<span class="logo-sm">
							<img src="/assets/images/logo-beta/other/logo-pictSQUARE-beta-white.png" alt="" height="10">
						</span>
					</a>

					<div class="h-100" id="left-side-menu-container" data-simplebar>
						<ul class="metismenu side-nav">
							<li class="side-nav-title side-nav-item">問い合わせ管理</li>
							<li class="side-nav-item">
								<a href="/tickets/index/my_yet" class="side-nav-link">
									<i class="fal fa-tag"></i>
									<span> チケット </span>
								</a>
							</li>
							<li class="side-nav-item">
								<a href="/forms/index" class="side-nav-link">
									<i class="fal fa-code"></i>
									<span> フォーム </span>
								</a>
							</li>
							<li class="side-nav-item">
								<a href="/templates/index" class="side-nav-link">
								<i class="far fa-comment-alt-lines"></i>
									<span> 回答文作成 </span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			<?} else {?>
				<div class="left-side-menu">
					<a href="/" class="logo text-center logo-light">
						<span class="logo-lg">
							<img src="/assets/images/logo-beta/other/logo-pictSQUARE-beta-white.png" alt="" height="30">
						</span>
						<span class="logo-sm">
							<img src="/assets/images/logo-beta/other/logo-pictSQUARE-beta-white.png" alt="" height="10">
						</span>
					</a>
					<div class="h-100" id="left-side-menu-container" data-simplebar>
						<!--- Sidemenu -->
						<ul class="metismenu side-nav">
							<li class="side-nav-title side-nav-item">ConvHUBに参加する</li>
							<li class="side-nav-item">
								<a href="/users/login" class="side-nav-link">
									<i class="fal fa-user-plus mr-2"></i>
									<span> ログイン </span>
								</a>
							</li>
							<li class="side-nav-item">
								<a href="javascript: void(0);" class="side-nav-link">
									<i class="fal fa-info-square"></i>
									<span> ConvHUBについて </span>
								</a>
								<ul class="side-nav-second-level" aria-expanded="false">
									<li>
										<a href="/statics/term">利用規約></a>
									</li>
									<li>
										<a href="/statics/policy">プライバシーポリシー</a>
									</li>
									<li>
										<a href="/statics/company">運営会社</a>
									</li>
									<li>
										<a href="/statics/toku">特商法取引法</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			<?}?>
			<div class="content-page" style="padding-bottom:0px;">
				<div class="content">
					<div class="navbar-custom">
						<?if(!empty($my_user)){?>
							<ul class="list-unstyled topbar-right-menu float-right mb-0">
								<li class="dropdown notification-list">
									<a class="nav-link" href="/attens/index" role="button" aria-haspopup="false" aria-expanded="false">
									<?if(@$atten_count > 0){?>
										<i class="dripicons-bell noti-icon text-danger"></i>
										<span class="badge badge-danger"><?=@$atten_count?></span>
									<?} else {?>
										<i class="dripicons-bell noti-icon"></i>
									<?}?>
									</a>
								</li>
								<li class="dropdown notification-list">
								<a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
								aria-expanded="false">
									<span class="account-user-avatar">
									<img src="/assets/images/user.png">
									</span>
									<span>
										<span class="account-user-name" style="margin-top:10px;">
										<?=esc($my_user->nickname)?>
										</span>
									</span>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
								<a href="/users/edit" class="dropdown-item notify-item">
										<i class="fal fa-cog mr-1"></i>
										<span>アカウント設定</span>
									</a>
									<a href="/teams/index" class="dropdown-item notify-item">
										<i class="fal fa-users mr-1"></i>
										<span>チーム設定</span>
									</a>
									<a href="/users/logout" class="dropdown-item notify-item">
										<i class="fal fa-sign-out mr-1"></i>
										<span>ログアウト</span>
									</a>
								</div>
							</li>
						</ul>
						<?}?>
						<button class="button-menu-mobile open-left disable-btn">
							<i class="mdi mdi-menu"></i>
						</button>
					</div>
					<div class="container-fluid">


						<div class="row">
							<div class="col-12 pb-5 pt-3">
								<?php echo $content ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- bundle -->
		<script <?=csp_script_nonce_test()?> src="/assets/js/vendor.js"></script>
		<script <?=csp_script_nonce_test()?> src="/assets/js/app.min.js"></script>
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
<script>
$(document).ready(function(){
    $('.copy_button').click(function(){
        var copyText = $(this).data("value");
		if(copyText !=""){
			var temp = $("<input>");
			$('body').append(temp);
			temp.val(copyText).select();
			document.execCommand("copy");
			temp.remove();
			info("コピーしました");
		}
    });
});
</script>