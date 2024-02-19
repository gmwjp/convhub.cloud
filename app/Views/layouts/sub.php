<!DOCTYPE html>
<html lang="ja">
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-3819515-48"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'UA-3819515-48');
		</script>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="keywords" content="同人即売会,オンライン,アバター,即売会,イベント,頒布" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="pinterest" content="nopin" />
		<meta name="description" content="pictSQUAREでは、オンラインで即売会に参加できます。オンライン会場では、アバターでチャットでき、その場で頒布物を注文することができます。" />
		<meta property="og:title" content="<?if(!empty($page_title)){?><?=$page_title?> | <?}?>pictSQUARE - オンライン即売会サービス" />
		<meta property="og:description" content="pictSQUAREでは、オンラインで即売会に参加できます。オンライン会場では、アバターでチャットでき、その場で頒布物を注文することができます。" />
		<meta property="og:url" content="https://pictsquare.net" />
		<meta property="og:image" content="https://pictsquare.net/assets/images/card.png" />
		<meta property="og:type" content="website" />
		<meta name="twitter:title" content="<?if(!empty($page_title)){?><?=$page_title?> | <?}?>pictSQUARE - オンライン即売会サービス" />
		<meta name="twitter:image" content="https://pictsquare.net/assets/images/card.png" />
		<meta name="twitter:url" content="https://pictsquare.net" />
		<meta name="twitter:site" content="@pictsquarenet" />
		<meta name="twitter:card" content="summary" />
		<?if(!empty($meta)){?>
			<?foreach($meta as $key => $val){?>
				<?foreach($val as $key2 => $val2){?>
					<meta name="<?=$key2?>" content="<?=$val2?>">
				<?}?>
			<?}?>
		<?}?>
		<title><?if(!empty($page_title)){?><?=$page_title?> | <?}?>pictSQUARE - オンライン即売会サービス</title>
		<link rel="shortcut icon" href="/assets/images/favicon.ico">
		<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
		<link href="/assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
		<link href="/assets/css/notifIt.css" rel="stylesheet" media="screen">
		<link href="/assets/css/pictsquare.css" rel="stylesheet" medit="screen">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
		<script src="/assets/js/opnet.js?d=20200510_5"></script>
		<script src="/assets/js/notifIt.min.js"></script>
		<script type="text/javascript" src="https://ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-12 pb-5">
					<?=$content?>
				</div>
			</div>
		</div>
        <script src="/assets/js/vendor.min.js"></script>
        <script src="/assets/js/app.min.js"></script>
    </body>
</html>
