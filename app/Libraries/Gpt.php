<?
namespace App\Libraries;
class Gpt {
    function generateSummary($text){
		$apiUrl = 'https://api.openai.com/v1/chat/completions';
		// 要約したいテキストと、日本語での応答を求める指示
		$messages = [
			["role" => "system", "content" => "私はWEBサービスの運営者です。ユーザーから以下の問い合わせがありました。以下のテキストを日本語でmax_tokensを400以内で要約してください:"],
			["role" => "user", "content" => $text]
		];
		// cURLセッションの初期化
		$ch = curl_init($apiUrl);
		// APIリクエストの設定
		$data = [
			'model' => 'gpt-4-0125-preview', // 使用するモデル
			'messages' => $messages,
			'temperature' => 0.7,
			'max_tokens' => 400
		];
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Authorization: Bearer ' . env("chatgpt.secret")
		]);
		// リクエストの実行と結果の処理
		$response = curl_exec($ch);
		// エラーチェック
		if(curl_errno($ch)) {
			//echo 'Error:' . curl_error($ch);
            $ret = false;
		} else {
			$decodedResponse = json_decode($response, true);
			if (isset($decodedResponse['choices'][0]['message']['content'])) {
                $ret = $decodedResponse['choices'][0]['message']['content'];
			} else {
                $ret = false;
				// レスポンス構造が期待と異なる場合はデバッグ情報を表示
				//var_dump($decodedResponse);
			}
		}
		curl_close($ch);
        return $ret;
    }
	function generateAnswer($prompt_body,$ticket_body,$text){
		$apiUrl = 'https://api.openai.com/v1/chat/completions';
		// 要約したいテキストと、日本語での応答を求める指示
		$messages = [
			["role" => "system", "content" => $prompt_body."\n--\n".$text],
			["role" => "user", "content" => $text]
		];
		// cURLセッションの初期化
		$ch = curl_init($apiUrl);
		// APIリクエストの設定
		$data = [
			'model' => 'gpt-4-0125-preview', // 使用するモデル
			'messages' => $messages,
			'temperature' => 0.7,
			'max_tokens' => 1000
		];
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Authorization: Bearer ' . env("chatgpt.secret")
		]);
		// リクエストの実行と結果の処理
		$response = curl_exec($ch);
		// エラーチェック
		if(curl_errno($ch)) {
			writeLog('Error:' . curl_error($ch),"critical");
			$ret = false;
			//echo 'Error:' . curl_error($ch);
            $ret = false;
		} else {
			$decodedResponse = json_decode($response, true);
			if (isset($decodedResponse['choices'][0]['message']['content'])) {
                $ret = $decodedResponse['choices'][0]['message']['content'];
			} else {
                $ret = false;
				writeLog('Error2:',"critical");
				writeLog($decodedResponse,"critical");
				// レスポンス構造が期待と異なる場合はデバッグ情報を表示
				//var_dump($decodedResponse);
			}
		}
		curl_close($ch);
        return $ret;
    }
}
?>
