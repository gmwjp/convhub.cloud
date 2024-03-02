<?php
namespace App\Controllers;
class Procs extends _MyController {
	function initSystem() {
		parent::initSystem();
		set_time_limit(0);
	}
	/*添付ファイルの定期削除処理*/
	function del_attaches(){
		//一定期間以上経過しているチケット情報を取得
		//※他に条件がある場合は変更・追加してください
		//※ひとまず「90日前以前」としていますが、要件に応じて適宜調整してください
		$tickets = $this->model("Tickets")->where("created < '".date("Y-m-d H:i:s", strtotime("-90 day"))."'")->where("attaches is not null")->find();
		//ファイル削除ループ
		foreach ($tickets as $ticket) {
			$attaches = json_decode($ticket->attaches);	//該当チケットデータに登録されている添付ファイル情報をJSON解析
			//添付ファイルループ
			foreach ($attaches as $attach) {
				unlink(WRITEPATH."file/attach/".$attach);	//該当ファイルの削除
			}
		}
	}
}