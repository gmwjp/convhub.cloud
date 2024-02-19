<?php
namespace App\Libraries;
use PHPMailer\PHPMailer\PHPMailer;
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
