<?php
namespace App\Controllers;
class Forms extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index(){
		$this->title("フォーム一覧");
		$this->hasUserSession();
		$this->set("forms",$forms = $this->model("Teams")->getForms($this->my_user->team_id));
		return $this->view("/forms/index");
	}
	function detail($id){
		$this->title("フォーム詳細");
		$this->hasUserSession();
		checkId($id);
		$form = $this->model("Forms")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($form){
			$this->set("form",$form);
			//項目を取得
			$this->set("form_items",$this->model("Forms")->getItems($form->id));
			$this->set("subforms",$this->model("Forms")->getSubforms($form->id));
			return $this->view("/forms/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function add(){
		$this->title("フォーム新規追加");
		$this->hasUserSession();
		//実行フラグを確認
		if(request()->getPost("execute")){
			//バリデーションの組み立て
			$this->model("Forms")->createValidation("add",request()->getPost());
			//バリデーションチェック
			if($this->model("Forms")->validates("add")){
				//保存
				request()->addPost("code",$this->model("Forms")->createCode());
				request()->addPost("team_id",$this->my_user->team_id);
				request()->addPost("user_id",$this->my_user->id);
				$form_id = $this->model("Forms")->write(request()->getPost());
				//前の項目は論理削除
				$this->model("Forms")->clearItems($form_id);
				//項目を保存
				if(request()->getPost("names")){
					foreach(request()->getPost("names") as $key => $val){
						unset($dat);
						$dat["form_id"] = $form_id;
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
						$this->model("Form_items")->write($dat);
					}
				}
				//リダイレクト
				session()->setFlashdata("message","フォームを追加しました");
				$this->redirect("/forms/index");
			}
		}
		return $this->view("/forms/add");
	}
	function edit($id){
		$this->title("フォーム編集");
		$this->hasUserSession();
		checkId($id);
		$form = $this->model("Forms")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($form){
			$this->set("form",$form);
			//項目を取得
			$this->set("form_items",$form_items = $this->model("Forms")->getItems($form->id));
			if(request()->getPost("execute")){
				//バリデーションの組み立て
				$this->model("Forms")->createValidation("edit",request()->getPost());
				if($this->model("Forms")->validates("edit")){
					//保存
					request()->addPost("id",$form->id);
					$this->model("Forms")->write(request()->getPost());
					//前の項目は論理削除
					$this->model("Forms")->clearItems($form->id);
					//項目を保存
					if(request()->getPost("names")){
						foreach(request()->getPost("names") as $key => $val){
							unset($dat);
							$dat["form_id"] = $form->id;
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
							$this->model("Form_items")->write($dat);
						}
					}
					session()->setFlashdata("message","フォームを編集しました");
					$this->redirect("/forms/index");
				}
			} else {
				request()->addPosts($form);
				unset($data);
				foreach($form_items as $key => $items){
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
			return $this->view("/forms/edit");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function del($id){
		$this->hasUserSession();
		checkId($id);
		$form = $this->model("Forms")->where("team_id",$this->my_user->team_id)->where("id",$id)->delete();
		//リダイレクト
		session()->setFlashdata("message","フォームを削除しました");
		$this->redirect("/forms/index");
	}
	function show($section,$code){
		$form = $this->model("Forms")->where("code",$code)->last();
		if($form){
			$this->set("form",$form);
			$this->title($form->name);
			//項目データを取得
			$this->set("form_items" ,$form_items = $this->model("Forms")->getItems($form->id));
			//サブフォームと項目データを取得
			$subforms = $this->model("Forms")->getSubforms($form->id);
			foreach($subforms as $key => $subform){
				$subforms[$key]->items = $this->model("Subforms")->getItems($subform->id);
			}
			$this->set("subforms",$subforms);
			if($section == "input"){
				//入力画面
				return $this->view("/forms/show/$section","form");
			}
			if($section == "confirm"){
				//確認画面
				if(request()->getPost("execute")){
					request()->addPost("subform",request()->getGet("subform"));
					//バリデーション組み立て
					$this->createInputValidation($code,request()->getPost());
					//バリデーションチェック
					if($this->model("Forms")->validates("input")){
						//バリデーションOK
						$items_temp = $this->library("Notion")->search($form->notion_secret,request()->getPost("title"));
						foreach($items_temp->results as $key => $item){
							$items_temp->results[$key]->detail = $this->library("Notion")->get_data($form->notion_secret,$item->id);
						}
						$this->set("items",$items_temp);
						return $this->view("/forms/show/confirm","form");		
					} else {
						//バリデーションエラー
						return $this->view("/forms/show/input","form");
					}
				} else {
					$this->redirect("/statics/error");
				}
			}
			if($section == "complate"){
				//完了画面
				if(request()->getPost("execute")){
					request()->addPost("subform",request()->getGet("subform"));
					//バリデーション組み立て
					$this->createInputValidation($code,request()->getPost());
					//バリデーションチェック
					if($this->model("Forms")->validates("input")){
						//バリデーションOK・データ保存
						request()->addPost("subform_id",request()->getGet("subform"));
						request()->addPost("form_id",$form->id);
						//基本データ
						$ticket_id = $this->model("Tickets")->write(request()->getPost());
						//追加項目
						if($form_items){
							foreach($form_items as $item){
								unset($dat);
								$dat["ticket_id"] = $ticket_id;
								$dat["form_item_id"] = $item->id;
								if($item->section == "checkbox"){
									$dat["value"] = implode("\n",request()->getPost("form_item_".$item->id));
								} else {
									$dat["value"] = request()->getPost("form_item_".$item->id);
								}
								$this->model("TicketFormItems")->write($dat);
							}
						}
						//サブフォーム追加項目
						if(request()->getGet("subform")){
							if($subforms){
								foreach($subforms as $subform){
									if($subform->id == request()->getGet("subform")){
										if($subform->items){
											foreach($subform->items as $item){
												unset($dat);
												$dat["ticket_id"] = $ticket_id;
												$dat["subform_item_id"] = $item->id;
												if($item->section == "checkbox"){
													$dat["value"] = implode("\n",request()->getPost("subform_item_".$item->id));
												} else {
													$dat["value"] = request()->getPost("subform_item_".$item->id);
												}
												$this->model("TicketSubformItems")->write($dat);
											}
										}
									}
								}
							}
						}
						//リダイレクトして完了画面を表示
						$this->redirect("/forms/show/complate/".$code);
					} else {
						//バリデーションエラー
						return $this->view("/forms/show/input","form");
					}
				} else {
					return $this->view("/forms/show/complate","form");
				}
			}
		} else {
			$this->redirect("/statics/error");
		}
	}
	function createInputValidation($code,$post){
		$form = $this->model("Forms")->where("code",$code)->last();
		if($form){
			$form_items = $this->model("Forms")->getItems($form->id);
			$subforms = $this->model("Forms")->getSubforms($form->id);
			if($subforms){
				foreach($subforms as $key => $subform){
					$subforms[$key]->items = $this->model("Subforms")->getItems($subform->id);
				}	
			}
			//基本フォームのバリデーション作成
			if($form_items){
				foreach($form_items as $item){
					$rules = [];
					if($item->required == 1){
						$rules[] = "required";
					}
					if($item->section == "textbox"){
						$rules[] = "max_length[255]";
					}
					if($item->section == "textarea"){
						$rules[] = "max_length[10000]";
					}
					$this->model("Forms")->validate["input"]["form_item_".$item->id] = [
						"rules" => implode("|",$rules)
					];
				}
			}
			if(!empty($post["subform"])){
				if($subforms){
					foreach($subforms as $key => $subform){
						if($subform->id == $post["subform"]){
							if($subforms[$key]->items){
								foreach($subforms[$key]->items as $item){
									$rules = [];
									if($item->required == 1){
										$rules[] = "required";
									}
									if($item->section == "textbox"){
										$rules[] = "max_length[255]";
									}
									if($item->section == "textarea"){
										$rules[] = "max_length[10000]";
									}
									$this->model("Forms")->validate["input"]["subform_item_".$item->id] = [
										"rules" => implode("|",$rules)
									];			
								}
							}
						}
					}	
				}	
			}
		}
	}
	
}
