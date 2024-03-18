<?php
namespace App\Controllers;
class Forms extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index(){
		$this->title("フォーム一覧");
		$this->hasPermission();
		$this->set("forms",$forms = $this->model("Teams")->getForms($this->my_user->team_id));
		return $this->view("/forms/index");
	}
	function detail($id){
		$this->title("フォーム詳細");
		$this->hasPermission();
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
		$this->hasPermission();
		//実行フラグを確認
		if(request()->getPost("execute")){
			//バリデーションの組み立て
			$this->model("Forms")->createValidation("add",request()->getPost());
			//バリデーションチェック
			if($this->model("Forms")->validates("add")){
				//保存
				request()->addPost("code",$code = $this->model("Forms")->createCode());
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
						$dat["about"] = request()->getPost("abouts")[$key];
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
				//画像の保存
				if(request()->getPost("image")["name"] !=""){
					$this->model("Forms")->image_resize(
						dirname(__FILE__)."/../../public/img/forms/".$code.".png",
						request()->getPost("image")["tmp_name"],
						null,30
					);
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
		$this->hasPermission();
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
							$dat["about"] = request()->getPost("abouts")[$key];
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
					if(request()->getPost("image")["name"] !=""){
						$this->model("Forms")->image_resize(
							dirname(__FILE__)."/../../public/img/forms/".$form->code.".png",
							request()->getPost("image")["tmp_name"],
							null,30
						);
					}
	
					session()->setFlashdata("message","フォームを編集しました");
					$this->redirect("/forms/index");
				}
			} else {
				request()->addPosts($form);
				unset($data);
				if($form_items){
					foreach($form_items as $key => $items){
						$data["required"][$key] = $items->required;
						$data["name"][$key] = $items->name;
						$data["section"][$key] = $items->section;
						$data["bodies"][$key] = $items->body;
						$data["abouts"][$key] = $items->about;
					}
					request()->addPost("names",$data["name"]);
					request()->addPost("select",$data["section"]);
					request()->addPost("required",$data["required"]);
					request()->addPost("bodies",$data["bodies"]);
					request()->addPost("abouts",$data["abouts"]);
				}

			}
			return $this->view("/forms/edit");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function del($id){
		$this->hasPermission();
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
			$this->set("widgets",$this->model("Forms")->getMostWidgets($form->id));
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
				session()->remove("files");
				return $this->view("/forms/show/$section","form");
			}
			if($section == "confirm"){
				//確認画面
				if(request()->getPost("execute")){
					request()->addPost("subform",request()->getGet("subform"));
					request()->addPostFiles("files",WRITEPATH."files/attach");
					session()->set("files",request()->getPost("files"));	//ファイル情報をセッションに書き込み
					//バリデーション組み立て
					$this->createInputValidation($code,request()->getPost());
					
					if($this->model("Forms")->validates("input")){
						//バリデーションOK
						$meishi = $this->library("Yahoo")->getMeishi(request()->getPost("title"));
						$items_temp = false;
						if($meishi){
							if($form->notion_secret !=""){
								$items_temp = $this->library("Notion")->search($form->notion_secret,implode(" ",$meishi));
							}
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
						request()->addPost("team_id",$form->team_id);
						request()->addPost("token",$this->model("forms")->createCode());
						if(request()->getPost("notion_title")){
							$notions = [];
							foreach(request()->getPost("notion_title") as $key => $val){
								$notions[$key] = [
									"title" => request()->getPost("notion_title")[$key],
									"url" => request()->getPost("notion_url")[$key],
									"read" =>request()->getPost("notion_read")[$key],
								];
							}	
							request()->addPost("notions",json_encode($notions));
						}
						//基本データ
						$ticket_id = $this->model("Tickets")->write(request()->getPost());
						//追加項目
						if($form_items){
							foreach($form_items as $item){
								unset($dat);
								$dat["ticket_id"] = $ticket_id;
								$dat["form_item_id"] = $item->id;
								$dat["title"] = $item->name;
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
												$dat["title"] = $item->name;
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
						//添付ファイル処理
						if(session()->get("files")){
							//基本データに添付ファイル情報を反映
							unset($dat);
							$dat["id"] = $ticket_id;
							$dat["attaches"] = json_encode(session()->get("files"));
							$this->model("Tickets")->write($dat);
							session()->remove("files");
						}
						if(request()->getPost("execute") == "on"){
							//自動返信メールを送信
							unset($text);
							$text["form_name"] = esc($form->name);
							$text["info"] = "";
							$text["info"] .= "件名：".esc(request()->getPost("title"))."\n";
							$text["info"] .= "問い合わせ内容：".esc(request()->getPost("body"))."\n";
							$this->library("SmtpMailer")->send("ticket",$text,request()->getPost("mail"));
							//リダイレクトして完了画面を表示
							$this->redirect("/forms/show/complate/".$code);							
						}
						if(request()->getPost("execute") == "answer"){
							//解決した
							unset($dat);
							$dat["id"] = $ticket_id;
							$dat["status"] = 2;
							$dat["user_id"] = -1;
							$this->model("Tickets")->write($dat);
							$this->redirect("/forms/show/complate2/".$code);
						}
					} else {
						//バリデーションエラー
						return $this->view("/forms/show/input","form");
					}
				} else {
					return $this->view("/forms/show/complate","form");
				}
			}
			if($section == "complate2"){
				return $this->view("/forms/show/complate2","form");
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
					$rules = false;
					if($item->required == 1){
						$rules[] = "required";
					}
					if($item->section == "textbox"){
						$rules[] = "max_length[255]";
					}
					if($item->section == "textarea"){
						$rules[] = "max_length[10000]";
					}
					if($rules){
						$this->model("Forms")->validate["input"]["form_item_".$item->id] = [
							"rules" => implode("|",$rules)
						];	
					}
				}
			}
			if(!empty($post["subform"])){
				if($subforms){
					foreach($subforms as $key => $subform){
						if($subform->id == $post["subform"]){
							if($subforms[$key]->items){
								foreach($subforms[$key]->items as $item){
									$rules = false;
									if($item->required == 1){
										$rules[] = "required";
									}
									if($item->section == "textbox"){
										$rules[] = "max_length[255]";
									}
									if($item->section == "textarea"){
										$rules[] = "max_length[10000]";
									}
									if($rules){
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
	function widgets($form_id){
		checkId($form_id);
		$this->hasPermission();
		$form = $this->model("Forms")->where("team_id",$this->my_user->team_id)->where("id",$form_id)->last();
		if($form){
			$this->set("form",$form);
			$this->title($form->name."：ウィジェット管理");
			$this->set("widgets",$this->model("Forms")->getWidgets($form->id));
			return $this->view("/forms/widgets");		 	
		} else {
			$this->redirect("/statics/error");
		}
	}
}
