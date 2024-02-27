<?php
namespace App\Libraries;
/**
 * Pagination Component, responsible for managing the DATA required for pagination.
 */
class Api {

	function ensureUTF8($data) {
		if (is_string($data)) {
			return iconv('UTF-8', 'UTF-8//IGNORE', $data);
		} elseif (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = $this->ensureUTF8($value);  // 再帰的に処理
			}
		}
		return $data;
	}
	function success($data = null){
		$data = $this->ensureUTF8($data);
		print json_encode(array("result"=>"success","data"=>$data), JSON_UNESCAPED_UNICODE);
		exit();
	}
	function error($message,$data=null){
		print json_encode(array("result"=>"error","message"=>$message,"data"=>$data), JSON_UNESCAPED_UNICODE);
		exit();
	}
}
?>
