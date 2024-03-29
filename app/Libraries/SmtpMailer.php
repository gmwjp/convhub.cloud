<?php
namespace App\Libraries;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;
class SmtpMailer {
	var $language = "ja";
	function send($template_file,$texts,$address,$from_address = null,$from_name = null,$from_subject = null){
		//言語設定、内部エンコーディングを指定する
		mb_language("japanese");
		mb_internal_encoding("UTF-8");
		// XMLファイルの読込み
		$data = simplexml_load_file(dirname(__FILE__).'/../Mails/'.$template_file.'.xml');
		//置き換え
		if($texts){
			foreach($texts as $key => $text){
				$data->Body = str_replace("{".$key."}", $text, $data->Body);
				$data->Subject = str_replace("{".$key."}", $text, $data->Subject);
			}
		}
		$mail = new PHPMailer();
		$mail->CharSet = "iso-2022-jp";
		$mail->Encoding = "7bit";
		$mail->AddAddress($address);

		$mail->IsSMTP();
		$mail->SMTPAuth = TRUE;
		$mail->SMTPSecure = 'tls';
		//$mail->SMTPDebug = TRUE;                 //「SMTP認証を使うよ」設定
		$mail->Host = env("smtp.host");    // SMTPサーバーアドレス:ポート番号
		$mail->Port = env("smtp.port");    // SMTPサーバーアドレス:ポート番号
		$mail->Username = env("smtp.user");      // SMTP認証用のユーザーID
		$mail->Password = env("smtp.pass");  // SMTP認証用のパスワード
		if($from_address == null){
			$mail->From = env("smtp.from");
		} else {
			$mail->From = $from_address;
		}
		if($from_name == null){
			$mail->FromName = mb_encode_mimeheader(mb_convert_encoding(env("smtp.from"),"JIS","UTF-8"));
		} else {
			$mail->FromName = mb_encode_mimeheader(mb_convert_encoding($from_name,"JIS","UTF-8"));
		}
		if($from_subject == null){
			$mail->Subject = mb_encode_mimeheader($data->Subject,"iso-2022-jp");
		} else {
			$mail->Subject = mb_encode_mimeheader($from_subject,"iso-2022-jp");
		}
		$mail->Body  = mb_convert_encoding($data->Body ,"JIS","UTF-8");
		$ret = $mail->Send();
		return $ret;
	}
	function gmail_send(){
		//
		//

		//refresh 1//049M8bGxo04xeCgYIARAAGAQSNwF-L9IrUblFGiDCHIjFOG5PIZeIfWw_lqc0ex1NUKnglRuc4ICDzUW_0Yp3ySUAZ-9SyJQrJB8
		//access ya29.a0Ad52N38Iwvt-9NuY8XGaKBmsdlV54R8t3_CUxoMb9vo6YHLojOcGs5KG4EJXb4N06faeeaU2pVARtsQVothuLznrXXexA1hA2lQXoEM04gYcgPEY7CgHXoGmfSZujbUx2Xup4XyIkyFAjfiv3-jmEWv04_reBxOUkjfHaCgYKAaISARESFQHGX2MiYEu9YQGXMqSGzjq52GD66Q0171
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPDebug = true;
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->SMTPAuth = true;
		$mail->AuthType = 'XOAUTH2';
		$provider = new Google(
			[
			  'clientId' => "50183193864-5n1umca4kjpicjv6ajk4in7luhqt9p19.apps.googleusercontent.com",
			  'clientSecret' => "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",
			]
		  );
		
		  $mail->setOAuth(
			new OAuth(
			  [
				'provider' => $provider,
				'clientId' => "50183193864-5n1umca4kjpicjv6ajk4in7luhqt9p19.apps.googleusercontent.com",
				'clientSecret' => "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",
				'refreshToken' => "1//049M8bGxo04xeCgYIARAAGAQSNwF-L9IrUblFGiDCHIjFOG5PIZeIfWw_lqc0ex1NUKnglRuc4ICDzUW_0Yp3ySUAZ-9SyJQrJB8",
				'userName' => "pictbland@g-m-w.jp",
			  ]
			)
		  );
		  $data = [
			"name" => "test",
			"mail" => "rhara@g-m-w.jp",
			"content" => "テストメールです"
		  ];
		  $mail->setFrom("pictbland@g-m-w.jp", 'site title');
  $mail->addAddress($data["mail"], $data["name"]);
  $mailBody = "
    お名前: {$data['name']}
    メールアドレス: {$data['mail']}
    お問い合わせ内容: {$data['content']}
  ";
  $mail->Subject = 'お問い合わせありがとうございます。';

  $mail->CharSet = PHPMailer::CHARSET_UTF8;
  $mail->Body = $mailBody;
		  $mail->send();

	}
	function sendData($title,$body,$attach = false,$address){
		//言語設定、内部エンコーディングを指定する
		mb_language("japanese");
		mb_internal_encoding("UTF-8");
		$mail = new PHPMailer();
		$mail->CharSet = "iso-2022-jp";
		$mail->Encoding = "7bit";
		$mail->AddAddress($address);
		$mail->IsSMTP();
		$mail->SMTPAuth = TRUE;
		$mail->SMTPSecure = 'tls';
		foreach((array)$attach as $atc){
			$mail->AddAttachment($atc);
		}
		$mail->Host = env("smtp.host");    // SMTPサーバーアドレス:ポート番号
		$mail->Port = env("smtp.port");    // SMTPサーバーアドレス:ポート番号
		$mail->Username = env("smtp.user");      // SMTP認証用のユーザーID
		$mail->Password = env("smtp.pass");  // SMTP認証用のパスワード
		$mail->From = env("smtp.from");
		$mail->FromName = mb_encode_mimeheader(mb_convert_encoding(env("smtp.name"),"JIS","UTF-8"));
		$mail->Subject = mb_encode_mimeheader($title,"iso-2022-jp");
		$mail->Body  = mb_convert_encoding($body ,"JIS","UTF-8");
		$ret = $mail->Send();
		return $ret;
	}
	function sendHtml($template_file,$texts,$address,$from_subject){
		//言語設定、内部エンコーディングを指定する
		mb_language("japanese");
		mb_internal_encoding("UTF-8");
		//インスタンス生成
		$mail = new PHPMailer();
		$mail->CharSet = "iso-2022-jp";
		$mail->Encoding = "7bit";
		$mail->AddAddress($address);
		//SMTP接続設定
		$mail->IsSMTP();
		$mail->SMTPAuth = TRUE;
		$mail->SMTPSecure = 'tls';
		//$mail->SMTPDebug = TRUE;                 //「SMTP認証を使うよ」設定
		$mail->Host = env("smtp.host");    // SMTPサーバーアドレス:ポート番号
		$mail->Port = env("smtp.port");    // SMTPサーバーアドレス:ポート番号
		$mail->Username = env("smtp.user");      // SMTP認証用のユーザーID
		$mail->Password = env("smtp.pass");  // SMTP認証用のパスワード
		$mail->From = env("smtp.from");
		//メール送信設定
		$mail->IsHTML(true);
		$mail->FromName = mb_encode_mimeheader(mb_convert_encoding(env("smtp.name"),"JIS","UTF-8"));
		$mail->Subject = mb_encode_mimeheader($from_subject,"iso-2022-jp");
		//HTMLファイルの読込み
		$body = file_get_contents(dirname(__FILE__).'/../Mails/'.$this->language."/"._site."/".$template_file.'.html');
		//置き換え
		if($texts){
			foreach($texts as $key => $text){
				$body = str_replace("{".$key."}", $text, $body);
			}
		}
		$mail->Body  = mb_convert_encoding($body,"JIS","UTF-8");

		$ret = $mail->Send();
		return $ret;
	}

}
?>
