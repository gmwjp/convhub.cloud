<?php
namespace App\Controllers;
class Templates extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index(){
		$this->title("テンプレート一覧");
		$this->hasUserSession();
		$this->set("templates",$templates = $this->model("Teams")->getTemplates($this->my_user->team_id));
		return $this->view("/templates/index");
	}
	function detail($id){
		$this->title("テンプレート詳細");
		$this->hasUserSession();
		checkId($id);
		$template = $this->model("Templates")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($template){
			$this->set("template",$template);
			return $this->view("/templates/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function add(){
		$this->title("テンプレート新規追加");
		$this->hasUserSession();
		//実行フラグを確認
		if(request()->getPost("execute")){
			//バリデーションチェック
			if($this->model("Templates")->validates("add")){
				//保存
				request()->addPost("team_id",$this->my_user->team_id);
				request()->addPost("user_id",$this->my_user->id);
				$template_id = $this->model("Templates")->write(request()->getPost());
				//リダイレクト
				session()->setFlashdata("message","テンプレートを追加しました");
				$this->redirect("/templates/index");
			}
		}
		return $this->view("/templates/add");
	}
	function edit($id){
		$this->title("テンプレート編集");
		$this->hasUserSession();
		checkId($id);
		$template = $this->model("Templates")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($template){
			$this->set("template",$template);
			if(request()->getPost("execute")){
				if($this->model("Templates")->validates("edit")){
					//保存
					request()->addPost("id",$template->id);
					$this->model("Templates")->write(request()->getPost());
					session()->setFlashdata("message","テンプレートを編集しました");
					$this->redirect("/templates/index");
				}
			} else {
				request()->addPosts($template);
			}
			return $this->view("/templates/edit");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function del($id){
		$this->hasUserSession();
		checkId($id);
		$template = $this->model("Templates")->where("team_id",$this->my_user->team_id)->where("id",$id)->delete();
		//リダイレクト
		session()->setFlashdata("message","テンプレートを削除しました");
		$this->redirect("/templates/index");
	}
}
