<?
namespace App\Libraries;
class Notion {
	function removeWidget($api_key, $page_id, $embed_url_prefix) {
		// ページのブロックをリストするNotion APIエンドポイント
		$blocks_list_url = 'https://api.notion.com/v1/blocks/' . $page_id . '/children';
		$ch = curl_init($blocks_list_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . $api_key,
			'Notion-Version: 2021-05-13'
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$blocks = json_decode($response, true)['results'];
	
		foreach ($blocks as $block) {
			if ($block['type'] == 'embed' && strpos($block['embed']['url'], $embed_url_prefix) !== -1) {
				// 一致するembedブロックが見つかった場合、そのブロックを削除
				$delete_url = 'https://api.notion.com/v1/blocks/' . $block['id'];
				$ch_delete = curl_init($delete_url);
				curl_setopt($ch_delete, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($ch_delete, CURLOPT_HTTPHEADER, [
					'Authorization: Bearer ' . $api_key,
					'Notion-Version: 2021-05-13'
				]);
				curl_exec($ch_delete);
				curl_close($ch_delete);
			}
		}
		curl_close($ch);
	}
	function pushWidget($api_key,$page_id,$embed_url){
		$data = [
			'parent' => ['type' => 'page_id', 'page_id' => $page_id],
			'properties' => new \stdClass(), // Notion API requires an empty object for properties when creating a block
			'children' => [
				[
					'object' => 'block',
					'type' => 'embed',
					'embed' => [
						'url' => "https".$_SERVER["HTTP_HOST"].$embed_url
					]
				]
			]
		];
		$ch = curl_init('https://api.notion.com/v1/blocks/' . $page_id . '/children');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . $api_key,
			'Notion-Version: 2021-05-13',
			'Content-Type: application/json'
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);
		return json_decode($response);
	}
    function search($secret,$body = ""){
		// cURLセッションを初期化
		$ch = curl_init();
		// cURLオプションを設定
		curl_setopt($ch, CURLOPT_URL, 'https://api.notion.com/v1/search');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		if($body){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['query' => $body]));
		}
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