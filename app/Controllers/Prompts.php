<?php
namespace App\Controllers;
class Prompts extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index(){
		$this->title("プロンプト一覧");
		$this->hasPermission();
		$this->set("prompts",$prompts = $this->model("Teams")->getPrompts($this->my_user->team_id));
		return $this->view("/prompts/index");
	}
	function detail($id){
		$this->title("プロンプト詳細");
		$this->hasPermission();
		checkId($id);
		$prompt = $this->model("Prompts")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($prompt){
			$this->set("prompt",$prompt);
			return $this->view("/prompts/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function add(){
		$this->title("プロンプト新規追加");
		$this->hasPermission();
		//実行フラグを確認
		if(request()->getPost("execute")){
			//バリデーションチェック
			if($this->model("Prompts")->validates("add")){
				//保存
				request()->addPost("team_id",$this->my_user->team_id);
				request()->addPost("user_id",$this->my_user->id);
				$prompt_id = $this->model("Prompts")->write(request()->getPost());
				//順番を保存
				unset($dat);
				$dat["id"] = $prompt_id;
				$dat["order_no"] = $prompt_id;
				$prompt_id = $this->model("Prompts")->write($dat);
				//リダイレクト
				session()->setFlashdata("message","プロンプトを追加しました");
				$this->redirect("/prompts/index");
			}
		}
		return $this->view("/prompts/add");
	}
	function edit($id){
		$this->title("プロンプト編集");
		$this->hasPermission();
		checkId($id);
		$prompt = $this->model("Prompts")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($prompt){
			$this->set("prompt",$prompt);
			if(request()->getPost("execute")){
				if($this->model("Prompts")->validates("edit")){
					//保存
					request()->addPost("id",$prompt->id);
					$this->model("Prompts")->write(request()->getPost());
					session()->setFlashdata("message","プロンプトを編集しました");
					$this->redirect("/prompts/index");
				}
			} else {
				request()->addPosts($prompt);
			}
			return $this->view("/prompts/edit");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function del($id){
		$this->hasPermission();
		checkId($id);
		$prompt = $this->model("Prompts")->where("team_id",$this->my_user->team_id)->where("id",$id)->delete();
		//リダイレクト
		session()->setFlashdata("message","プロンプトを削除しました");
		$this->redirect("/prompts/index");
	}
	function up($id){
		$this->hasPermission("form");
		checkId($id);
		$prompt = $this->model("Prompts")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($prompt){
			$prompts = $this->model("Prompts")->where("team_id",$this->my_user->team_id)->orderBy("order_no","asc")->findAll();
			foreach($prompts as $key => $t){
				if($t->id == $prompt->id){
					if(!empty($prompts[$key-1])){
						unset($dat);
						$dat["id"] = $prompt->id;
						$dat["order_no"] = $prompts[$key-1]->order_no;
						$this->model("Prompts")->write($dat);
						unset($dat);
						$dat["id"] = $prompts[$key-1]->id;
						$dat["order_no"] = $prompt->order_no;
						$this->model("Prompts")->write($dat);
					}
				}
			}
			$this->redirect("/prompts/index");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function down($id){
		$this->hasPermission("form");
		checkId($id);
		$prompt = $this->model("Prompts")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($prompt){
			$prompts = $this->model("Prompts")->where("team_id",$this->my_user->team_id)->orderBy("order_no","asc")->findAll();
			foreach($prompts as $key => $t){
				if($t->id == $prompt->id){
					if(!empty($prompts[$key+1])){
						unset($dat);
						$dat["id"] = $prompt->id;
						$dat["order_no"] = $prompts[$key+1]->order_no;
						$this->model("Prompts")->write($dat);
						unset($dat);
						$dat["id"] = $prompts[$key+1]->id;
						$dat["order_no"] = $prompt->order_no;
						$this->model("Prompts")->write($dat);
					}
				}
			}
			$this->redirect("/prompts/index");
		} else {
			$this->redirect("/statics/error");
		}

	}
}
