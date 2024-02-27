<?php
namespace App\Controllers;
class Subforms extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function detail($id){
		$this->title("サブフォーム詳細");
		$this->hasUserSession();
		checkId($id);
		$subform = $this->model("Subforms")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($subform){
			$this->set("subform",$subform);
			//項目を取得
			$this->set("subform_items",$this->model("Subforms")->getItems($subform->id));
			return $this->view("/subforms/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function add($form_id){
		$this->title("サブフォーム新規追加");
		$this->hasUserSession();
		checkId($form_id);
		$form = $this->model("Forms")->where("team_id",$this->my_user->team_id)->where("id",$form_id)->last();
		if($form){
			$this->set("form",$form);
			//実行フラグを確認
			if(request()->getPost("execute")){
				//バリデーションの組み立て
				$this->model("Subforms")->createValidation("add",request()->getPost());
				//バリデーションチェック
				if($this->model("Subforms")->validates("add")){
					//保存
					request()->addPost("form_id",$form_id);
					request()->addPost("user_id",$this->my_user->id);
					request()->addPost("team_id",$this->my_user->team_id);
					$subform_id = $this->model("Subforms")->write(request()->getPost());
					//順番を保存
					unset($dat);
					$dat["id"] = $subform_id;
					$dat["order_no"] = $subform_id;
					$subform_id = $this->model("Subforms")->write($dat);
					//前の項目は論理削除
					$this->model("Subforms")->clearItems($subform_id);
					//項目を保存
					if(request()->getPost("names")){
						foreach(request()->getPost("names") as $key => $val){
							unset($dat);
							$dat["subform_id"] = $subform_id;
							$dat["user_id"] = $this->my_user->id;
							$dat["section"] = request()->getPost("select")[$key];
							if(!empty(request()->getPost("required")[$key])){
								$dat["required"] = 1;
							} else {
								$dat["required"] = 0;
							}
							$dat["name"] = request()->getPost("names")[$key];
							$dat["order_no"] = $key;
							if($dat["section"] == "radio" || $dat["section"] == "checkbox"){
								$dat["body"] = request()->getPost("bodies")[$key];
							}
							$this->model("Subform_items")->write($dat);
						}
					}
					//リダイレクト
					session()->setFlashdata("message","サブフォームを追加しました");
					$this->redirect("/forms/detail/".$form_id);
				}
			}
			return $this->view("/subforms/add");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function edit($id){
		$this->title("フォーム編集");
		$this->hasUserSession();
		checkId($id);
		$subform = $this->model("Subforms")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($subform){
			$this->set("subform",$subform);
			//項目を取得
			$this->set("subform_items",$subform_items = $this->model("Subforms")->getItems($subform->id));

			if(request()->getPost("execute")){
				//バリデーションの組み立て
				$this->model("Subforms")->createValidation("edit",request()->getPost());
				if($this->model("Subforms")->validates("edit")){
					//サブフォーム本体を保存
					request()->addPost("id",$subform->id);
					$this->model("Subforms")->write(request()->getPost());
					//サブフォーム項目を保存
					$this->model("Subforms")->clearItems($id);//前の項目は論理削除
					//項目を保存
					if(request()->getPost("names")){
						foreach(request()->getPost("names") as $key => $val){
							unset($dat);
							$dat["subform_id"] = $subform->id;
							$dat["user_id"] = $this->my_user->id;
							$dat["section"] = request()->getPost("select")[$key];
							if(!empty(request()->getPost("required")[$key])){
								$dat["required"] = 1;
							} else {
								$dat["required"] = 0;
							}
							$dat["name"] = request()->getPost("names")[$key];
							$dat["order_no"] = $key;
							if($dat["section"] == "radio" || $dat["section"] == "checkbox"){
								$dat["body"] = request()->getPost("bodies")[$key];
							}
							$this->model("Subform_items")->write($dat);
						}
					}
					session()->setFlashdata("message","サブフォームを編集しました");
					$this->redirect("/forms/detail/".$subform->form_id);
				}
			} else {
				request()->addPosts($subform);
				unset($data);
				foreach($subform_items as $key => $items){
					$data["required"][$key] = $items->required;
					$data["name"][$key] = $items->name;
					$data["section"][$key] = $items->section;
					$data["bodies"][$key] = $items->body;
				}
				request()->addPost("names",$data["name"]);
				request()->addPost("select",$data["section"]);
				request()->addPost("required",$data["required"]);
				request()->addPost("bodies",$data["bodies"]);
			}
			return $this->view("/subforms/edit");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function del($id){
		$this->hasUserSession();
		checkId($id);
		$subform = $this->model("Subforms")->where("team_id",$this->my_user->team_id)->where("id",$id)->delete();
		//リダイレクト
		session()->setFlashdata("message","サブフォームを削除しました");
		$this->redirect("/forms/detail/".$subform->form_id);
	}
}
