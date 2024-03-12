<?php
namespace App\Controllers;
class Widgets extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function detail($id){
		$this->title("ウィジェット詳細");
		$this->hasPermission();
		checkId($id);
		$widget = $this->model("Widgets")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($widget){
			$this->set("widget",$widget);
			$this->set("form",$this->model("Forms")->find($widget->form_id));
			return $this->view("/widgets/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
	
	function sync_data($form_id){
		set_time_limit(0);
		checkId($form_id);
		$this->hasPermission("widget");
		$form = $this->model("Forms")->where("team_id",$this->my_user->team_id)->where("id",$form_id)->last();
		if($form){
			$this->set("form",$form);
			$this->set("ignores",$ignores = $this->model("Teams")->getIgnores($this->my_user->team_id));
			//実行フラグを確認
			if(request->getPost("execute")){
				//更新フラグをOFFに
				$widgets = $this->model("Widgets")->where("form_id", $form->id)->where("team_id", $this->my_user->team_id)->findAll();
				foreach($widgets as $w){
					$this->model("Widgets")->write([
						"id" => $w->id,
						"sync_temp_flg" => 0
					]);
				}
				$cursor = null;
				$count = 0;
				do {
					$page_data = $this->library("Notion")->search($form->notion_secret, "" ,$cursor);
					if($page_data->results){
						foreach($page_data->results as $ret){
							if($ret->object == "page"){
								//ウィジェットを設置するのは最下の記事ページのみ
								if(!empty($ret->properties->title)){
									$name = $ret->properties->title->title[0]->plain_text;
									//このページに作成されているウィジェットはあるか
									$widget = $this->model("Widgets")->where("notion_page_id", $ret->id)->where("team_id", $this->my_user->team_id)->last();
									if(!$widget){
										//ない場合は新規作成
										$dat = [
											"team_id" => $this->my_user->team_id,
											"user_id" => $this->my_user->id,
											"section" => "feedback",
											"code" => $this->model("Widgets")->createCode(),
											"form_id" => $form->id,
											"notion_page_id" => $ret->id,
											"notion_url" => $ret->url,
											"name" => $name,
											"sync_temp_flg" => 1	//更新したフラグをONに
										];
										$this->model("Widgets")->write($dat);
										//ゴミがあったら削除
										$this->library("Notion")->removeWidget($form->notion_secret, $dat["notion_page_id"], "/widgets/show/");
										//ウィジェットを設置
										$this->library("Notion")->pushWidget($form->notion_secret, $dat["notion_page_id"],"/widgets/show/".$dat["code"]);
										$count++;    
									} else {
										//ページ名を更新しておく
										$dat = [
											"id" => $widget->id,
											"name" => $name,
											"sync_temp_flg" => 1	//更新したフラグをONに
										];
										$this->model("Widgets")->write($dat);
									}
									sleep(1);
								}
							}
						}
					}
					$cursor = $page_data->has_more ? $page_data->next_cursor : null;
					sleep(1);
				} while ($page_data->has_more);
				//更新フラグがOFFのものを削除
				$this->model("Widgets")->where("sync_temp_flg",0)->where("team_id",$this->my_user->team_id)->delete();
				session()->setFlashdata("message","{$count}件のウィジェットを同期しました");
				$this->redirect("/forms/widgets/".$form->id);
			}
			// return $this->view("/widgets/sync_data");
		} else {
			$this->redirect("/statics/error");
		}

	}
	function add($section){
		if($section == "feedback"){
			$this->title("ウィジェット追加：フィードバック");
		} else {
			$this->redirect("/statics/error");
		}
		$this->set("section",$section);
		$this->hasPermission("widget");
		$this->set("forms",$this->model("Teams")->getForms($this->my_user->team_id));
		//実行フラグを確認
		if(request()->getPost("execute")){
			//バリデーションチェック
			if($this->model("Widgets")->validates("add")){
				//保存
				request()->addPost("code",$this->model("Widgets")->createCode());
				request()->addPost("section",$section);
				request()->addPost("team_id",$this->my_user->team_id);
				request()->addPost("user_id",$this->my_user->id);
				$widget_id = $this->model("Widgets")->write(request()->getPost());
				//リダイレクト
				session()->setFlashdata("message","ウィジェットを追加しました");
				$this->redirect("/widgets/index/$section");
			}
		}
		return $this->view("/widgets/add");
	}
	function edit($section ,$id){
		if($section == "feedback"){
			$this->title("ウィジェット編集：フィードバック");
		} else {
			$this->redirect("/statics/error");
		}
		$this->set("section",$section);
		$this->hasPermission("widget");
		checkId($id);
		$widget = $this->model("Widgets")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($widget){
			$this->set("widget",$widget);
			$this->set("forms",$this->model("Teams")->getForms($this->my_user->team_id));
			if(request()->getPost("execute")){
				if($this->model("Widgets")->validates("edit")){
					//保存
					request()->addPost("id",$widget->id);
					$this->model("Widgets")->write(request()->getPost());
					session()->setFlashdata("message","ウィジェットを編集しました");
					$this->redirect("/widgets/index/$section");
				}
			} else {
				request()->addPosts($widget);
			}
			return $this->view("/widgets/edit");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function del($id){
		$this->hasPermission("widget");
		checkId($id);
		$widget = $this->model("Widgets")->where("team_id",$this->my_user->team_id)->where("id",$id)->delete();
		//リダイレクト
		session()->setFlashdata("message","ウィジェットを削除しました");
		$this->redirect("/widgets/index/".$widget->section);
	}
	function show($code){
		$widget = $this->model("Widgets")->where("code",$code)->last();
		if($widget){
			$this->set("widget",$widget);
			$this->set("form",$this->model("Forms")->find($widget->form_id));
			return $this->view("/widgets/show","widget");
		} else {
			print "widgetが見つかりません:{$code}";
		}
	}
	public function get_token(){
		$response = [
			'value'=> csrf_hash()
		];
		return response()->setJSON($response);
	}
	function exec($code){
		$widget = $this->model("Widgets")->where("code",$code)->last();
		if($widget){
			unset($dat);
			$dat["id"] = $widget->id;
			if(request()->getGet("action") == "view"){
				$dat["view_count"] = $widget->view_count+1;
				$widget_id = $this->model("Widgets")->write($dat);
				$this->library("Api")->success();	
			}
			if(request()->getGet("action") == "answer"){
				if(request()->getGet("param") == "yes"){
					$dat["yes_count"] = $widget->yes_count+1;
				} else if(request()->getGet("param") == "no"){
					$dat["no_count"] = $widget->no_count+1;
				}
				$widget_id = $this->model("Widgets")->write($dat);
				//データ取得し直し
				$widget = $this->model("Widgets")->find($widget_id);
			//session()->set("answer",$widget->id);
				$this->library("Api")->success(["yes_count"=>$widget->yes_count,"no_count"=>$widget->no_count,"all_count"=>$widget->yes_count+$widget->no_count]);
			}
		}
	}
}
