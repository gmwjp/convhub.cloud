<?php
namespace App\Controllers\Api;
use App\Controllers\_MyController;
class Errors extends _MyController {
	//エラーログのファイル名一覧を取得
	function get_loglist($segment = false){
		$files = [];
		foreach(glob(dirname(__FILE__)."/../../../writable/logs/*.log") as $file){
			if(!$segment || strpos($file,$segment) !== false){
				//区分が設定されていないか、ファイル名にその区分が含まれる場合
				$file = str_replace("log-","",basename($file));
                $file = str_replace(".log","",basename($file));
                $files[] = $file;
			}
		}
		$this->library('api')->success($files);
	}
	function get_logtxt($fname){
        $fname = "log-".$fname.".log";
		$file = dirname(__FILE__)."/../../../writable/logs/".$fname;
		if(file_exists($file)){
		    $data = file_get_contents($file);
			$this->library('api')->success($data);
		} else{
			$this->library('api')->error("ファイルが存在しません");
		}
	}
}
?>
