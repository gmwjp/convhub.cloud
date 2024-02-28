<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
    @media (prefers-color-scheme: light) {
      body {
        background-color: #ffffff; /* 明るい背景色 */
        color: #000000; /* 暗いテキスト色 */
      }
    }
    @media (prefers-color-scheme: dark) {
      body {
        background-color: #343a40; /* Bootstrapのダークテーマの背景色に合わせた */
        color: #ffffff; /* 明るいテキスト色 */
      }
    }
	/* 中央配置のためのスタイル */
	body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .centered-content {
            text-align: center;
        }
    </style>
	<script>
		var csrf_token_name = '<?=csrf_token()?>';
		var csrf_token_value = '<?=csrf_hash()?>';
		function postData(url,data,done_func=null,fail_func=null){
			$.ajax({
				url: '/widgets/get_token',
				method: 'GET',
				dataType: 'json',
				success: function(data) {
					csrf_token_value = data.value;
					console.log("token:"+csrf_token_value);
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
  <body>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
	<div class="centered-content">
	<?=$content?>
	</div>
  </body>
</html>
