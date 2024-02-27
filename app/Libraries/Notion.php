<?
namespace App\Libraries;
class Notion {
    function search($secret,$body){
		// cURLセッションを初期化
		$ch = curl_init();
		// cURLオプションを設定
		curl_setopt($ch, CURLOPT_URL, 'https://api.notion.com/v1/search');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['query' => $body]));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . $secret,
			'Notion-Version: 2022-06-28',
			'Content-Type: application/json'
		]);
		// リクエストを実行し、レスポンスを取得
		$response = curl_exec($ch);
		// エラーがある場合は表示
		if(curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		// cURLセッションを終了
		curl_close($ch);
		// レスポンスを表示
		return json_decode($response);
	}
	function get_data($secret,$page_id){
		// cURLセッションを初期化
		$ch = curl_init();
		// cURLオプションを設定
		curl_setopt($ch, CURLOPT_URL, "https://api.notion.com/v1/blocks/" . $page_id."/children");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . $secret,
			'Notion-Version: 2022-06-28'
		]);
		// リクエストを実行し、レスポンスを取得
		$response = curl_exec($ch);
		// エラーがある場合は表示
		if(curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		// cURLセッションを終了
		curl_close($ch);
		// レスポンスを表示
		return json_decode($response);
	}
}