<?php
namespace App\Controllers;
class Widgets extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index($section){
		if($section == "feedback"){
			$this->title("ウィジェット一覧：フィードバック");
		} else {
			$this->redirect("/statics/error");
		}
		$this->set("section",$section);
		$this->hasUserSession();
		$this->set("widgets",$this->model("Teams")->getWidgets($this->my_user->team_id,$section));
		return $this->view("/widgets/index");
	}
	function detail($section,$id){
		if($section == "feedback"){
			$this->title("ウィジェット詳細：フィードバック");
		} else {
			$this->redirect("/statics/error");
		}
		$this->set("section",$section);
		$this->hasUserSession();
		checkId($id);
		$widget = $this->model("Widgets")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($widget){
			$this->set("widget",$widget);
			return $this->view("/widgets/detail");
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
		$this->hasUserSession();
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
		$this->hasUserSession();
		checkId($id);
		$widget = $this->model("Widgets")->where("team_id",$this->my_user->team_id)->where("id",$id)->last();
		if($widget){
			$this->set("widget",$widget);
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
		$this->hasUserSession();
		checkId($id);
		$widget = $this->model("Widgets")->where("team_id",$this->my_user->team_id)->where("id",$id)->delete();
		//リダイレクト
		session()->setFlashdata("message","ウィジェットを削除しました");
		$this->redirect("/widgets/index");
	}
	function show($code){
		
		$widget = $this->model("Widgets")->where("code",$code)->last();
		if($widget){
			$this->set("widget",$widget);
			
			$this->set("csrf_token",$csrf_token = bin2hex(random_bytes(32)));
			writeLog("csrf_token","critical");
			session()->set('csrf_token', $csrf_token);

			return $this->view("/widgets/show","widget");
		} else {
			print "widgetが見つかりません";
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
				writeLog(session()->get("csrf_token").":".request()->getPost("csrf_token"),"critical");
				$dat["view_count"] = $widget->view_count+1;
				$widget_id = $this->model("Widgets")->write($dat);
				$this->library("Api")->success();
			}
			if(request()->getGet("action") == "answer"){
				writeLog(session()->get("csrf_token").":".request()->getPost("csrf_token"),"critical");
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
