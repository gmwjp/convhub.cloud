<?php
namespace App\Controllers;
class Ignores extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index(){
		$this->title("除外一覧");
		$this->hasPermission();
		$this->set("ignores",$ignores = $this->model("Teams")->getIgnores($this->my_user->team_id));
		return $this->view("/ignores/index");
	}
	function add(){
		$this->title("除外新規追加");
		$this->hasPermission("widget");
		//実行フラグを確認
		if(request()->getPost("execute")){
			//バリデーションチェック
			if($this->model("ignores")->validates("add")){
				//保存
				request()->addPost("team_id",$this->my_user->team_id);
				request()->addPost("user_id",$this->my_user->id);
				$ignore_id = $this->model("ignores")->write(request()->getPost());
				//リダイレクト
				session()->setFlashdata("message","除外設定を追加しました");
				$this->redirect("/ignores/index");
			}
		}
		return $this->view("/ignores/add");
	}
	function del($id){
		$this->hasPermission("widget");
		checkId($id);
		$this->model("ignores")->where("team_id",$this->my_user->team_id)->where("id",$id)->delete();
		//リダイレクト
		session()->setFlashdata("message","除外設定を削除しました");
		$this->redirect("/ignores/index");
	}
}
