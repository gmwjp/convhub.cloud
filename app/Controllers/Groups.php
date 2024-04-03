<?php
namespace App\Controllers;
class Groups extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index(){
		$this->title("グループ一覧");
		$this->hasPermission();
		$this->set("groups",$groups = $this->model("Teams")->getGroups($this->my_user->team_id));
		return $this->view("/groups/index");
	}
	function detail($id){
		$this->title("グループ詳細");
		$this->hasPermission();
		checkId($id);
		$group = $this->model("Groups")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($group){
			$this->set("group",$group);
			return $this->view("/groups/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function add(){
		$this->title("グループ新規追加");
		$this->hasPermission();
		//実行フラグを確認
		if(request()->getPost("execute")){
			//バリデーションチェック
			if($this->model("Groups")->validates("add")){
				//保存
				request()->addPost("team_id",$this->my_user->team_id);
				request()->addPost("user_id",$this->my_user->id);
				$group_id = $this->model("Groups")->write(request()->getPost());
				//リダイレクト
				session()->setFlashdata("message","グループを追加しました");
				$this->redirect("/groups/index");
			}
		}
		return $this->view("/groups/add");
	}
	function edit($id){
		$this->title("グループ編集");
		$this->hasPermission();
		checkId($id);
		$group = $this->model("Groups")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($group){
			$this->set("group",$group);
			if(request()->getPost("execute")){
				if($this->model("Groups")->validates("edit")){
					//保存
					request()->addPost("id",$group->id);
					$this->model("Groups")->write(request()->getPost());
					session()->setFlashdata("message","グループを編集しました");
					$this->redirect("/groups/index");
				}
			} else {
				request()->addPosts($group);
			}
			return $this->view("/groups/edit");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function del($id){
		$this->hasPermission();
		checkId($id);
		$group = $this->model("Groups")->where("team_id",$this->my_user->team_id)->where("id",$id)->delete();
		//リダイレクト
		session()->setFlashdata("message","グループを削除しました");
		$this->redirect("/groups/index");
	}
}
