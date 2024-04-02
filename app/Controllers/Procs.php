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
	//summry_flg = 0のチケット一覧を取得し、要約処理を実行する。要約処理が完了したものはsummry_flg = 1に更新する
	//要約処理が失敗したものはsummry_flg = 2に更新する
	function generate_summary(){
		$tickets = $this->model("Tickets")->where("summary_flg",0)->findAll();
		foreach ($tickets as $ticket) {
			$form = $this->model("Forms")->find($ticket->form_id);
			$summary = $this->library("Gpt")->generateSummary($form->name."\n".$ticket->body);
			if($summary){
				unset($dat);
				$dat["id"] = $ticket->id;
				$dat["summary"] = $summary;
				$dat["summary_flg"] = 1;
				$this->model("Tickets")->write($dat);
			} else {
				$dat["summary_flg"] = 2;
			}
		}
	}
}