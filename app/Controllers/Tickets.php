<?php
namespace App\Controllers;
class Tickets extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index($section){
		$this->title("チケット一覧");
		$this->hasPermission();
		$this->set("section",$section);
		//none,my_yet,my_end,all_yet,all_end,auto_end,all
		$params = [
			"none" => [
				"user_id" => "NULL",
				"status" => 1
			],
			"my_yet" => [
				"user_id" => $this->my_user->id,
				"status" => 1
			],
			"group_yet" => [
				"group_id" => $this->my_user->group_id,
				"status" => 1
			],
			"my_end" => [
				"user_id" => $this->my_user->id,
				"status" => 2
			],
			"all_yet" => [
				"status" => 1
			],
			"all_end" => [
				"status" => 2
			],
			"auto_end" => [
				"user_id" => -1,
				"status" => 2
			],
			"all" => []
		];
		$this->set("params",$params);
		foreach($params as $key => $val){
			$ticket_nums[$key] = $this->model("Tickets")->getNum($this->my_user->team_id,$val);
			if($key == $section){
				foreach($val as $key2 => $val2){
					request()->addGet($key2,$val2);
				}
			}
		}
		$this->set("ticket_nums",$ticket_nums);
		$this->set("users",$this->model("Teams")->getUsers($this->my_user->team_id));
		$this->set("forms",$this->model("Teams")->getForms($this->my_user->team_id));

		$tickets_all = $this->model("Tickets")->getIndex($this->my_user->team_id,request()->getGet());
		$tickets = $this->library("pagenate")->getPageData($tickets_all,_def_page_num,$this->library("pagenate")->getPage());
		$this->set("page",$this->library("pagenate")->getPage());
		$this->set("total", count($tickets_all));
		$this->set("tickets",$tickets);
		return $this->view("/tickets/index");
	}
	function sums($ym = false){
		if(!$ym){
			$ym = date("Y-m");
		}
		$this->hasPermission();
		$this->title("チケット集計：".$ym);
		$this->set("ym",$ym);
		
		$this->set("users",$this->model("Teams")->getUsers($this->my_user->team_id));
		$this->set("groups",$this->model("Teams")->getGroups($this->my_user->team_id));
		$this->set("forms",$this->model("Teams")->getForms($this->my_user->team_id));
		$param["start_date"] = $ym."-01";
		$param["end_date"] = $ym."-".date("t",strtotime($ym."-01"));
		$this->set("param",$param);
		$this->set("next_month" ,date("Y-m",strtotime($ym."-01 +1 month")));
		$this->set("prev_month" ,date("Y-m",strtotime($ym."-01 -1 month")));
		$this->set("tickets",$this->model("Tickets")->getIndex($this->my_user->team_id,$param));
		return $this->view("/tickets/sums");
	}
	function detail($ticket_id){
		$this->hasPermission();
		checkId($ticket_id);
		$ticket = $this->model("Tickets")->find($ticket_id);
		if($ticket){
			$this->title($ticket->title);
			$this->set("users",$this->model("Teams")->getUsers($this->my_user->team_id));
			$this->set("templates",$this->model("Teams")->getTemplates($this->my_user->team_id));
			$this->set("form",$form = $this->model("Forms")->find($ticket->form_id));	
			if($ticket->subform_id){
				$this->set("subform",$subform = $this->model("Subforms")->find($ticket->subform_id));	
			}
			$this->set("ticket",$ticket);
			$this->set("ticket_form_items",$this->model("Tickets")->getFormItems($ticket->id));
			$this->set("ticket_subform_items",$this->model("Tickets")->getSubformItems($ticket->id));
			//過去のチケットを取得
			$this->set("old_tickets",$this->model("Tickets")->getOldTickets($ticket->id));
			//コメントを取得
			$this->set("comments",$this->model("Tickets")->getAllComments($ticket->id));

			if(request()->getPost("execute")){
				request()->addPostFiles("files",WRITEPATH."files/attach");
				//バリデーションチェック
				if($this->model("Comments")->validates("add")){
					//データ保存
					request()->addPost("user_section","user");
					request()->addPost("user_id",$this->my_user->id);
					request()->addPost("ticket_id",$ticket->id);
					$comment_id = $this->model("Comments")->write(request()->getPost());
					if(request()->getPost("files")){
						//基本データに添付ファイル情報を反映
						unset($dat);
						$dat["id"] = $comment_id;
						$dat["attaches"] = json_encode(request()->getPost("files"));
						$this->model("Comments")->write($dat);
					}

					//問い合わせ主にメール送信
					if(request()->getPost("public_flg") == 1){
						unset($text);
						$text["form_name"] = esc($form->name);
						$text["url"] = $_SERVER["SITE_URL"]."/tickets/show/".$this->library("Crypt2")->encode($ticket->id);
						$this->library("SmtpMailer")->send("send_by_user",$text,$ticket->mail);
					}
					//チケットの状態を変更
					if(request()->getPost("public_flg") == 0){
						//社内メモの場合
						unset($dat);
						$dat["id"] = $ticket->id;
						$dat["status"] = 1;	//対応中
						$this->model("Tickets")->write($dat);
					} else {
						//パブリック返信
						unset($dat);
						$dat["id"] = $ticket->id;
						$dat["status"] = 2;	//完了
						$dat["user_id"] = $this->my_user->id;//担当者を変更
						$this->model("Tickets")->write($dat);
					}
					//リダイレクト
					session()->setFlashdata("message","コメントを送信しました");
					$this->redirect("/tickets/detail/".$ticket->id);
				}
			}

			return $this->view("/tickets/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function show($crypt_ticket_id){
		$ticket_id = $this->library("Crypt2")->decode($crypt_ticket_id);
		checkId($ticket_id);
		$ticket = $this->model("Tickets")->find($ticket_id);
		
		if($ticket){
			$this->set("crypt_ticket_id",$crypt_ticket_id);
			$this->set("form",$form = $this->model("Forms")->find($ticket->form_id));	
			$this->set("widgets",$this->model("Forms")->getMostWidgets($form->id));
			$this->title($form->name);
			if($ticket->subform_id){
				$this->set("subform",$this->model("Subforms")->find($ticket->subform_id));	
			}
			$this->set("ticket",$ticket);
			$this->set("ticket_form_items",$this->model("Tickets")->getFormItems($ticket->id));
			$this->set("ticket_subform_items",$this->model("Tickets")->getSubformItems($ticket->id));
			//コメントを取得
			$this->set("comments",$comments = $this->model("Tickets")->getComments($ticket->id));
			//既読処理
			foreach($comments as $comment){				
				if($comment->read_datetime == "" && $comment->user_section == "user"){
					unset($dat);
					$dat["id"] = $comment->id;
					$dat["read_datetime"] = date("Y-m-d H:i:s");
					$this->model("Comments")->write($dat);
				}
			}
			if(request()->getPost("execute")){
				//バリデーションチェック
				request()->addPost("user_section","customer");
				request()->addPost("ticket_id",$ticket->id);
				request()->addPost("public_flg",1);
				request()->addPostFiles("files",WRITEPATH."files/attach");
				if($this->model("Comments")->validates("add")){
					$comment_id = $this->model("Comments")->write(request()->getPost());
					//未対応に切り替え
					unset($dat);
					$dat["id"] = $ticket->id;
					$dat["status"] = 1;
					$this->model("Tickets")->write($dat);
					//添付ファイルを処理
					if(request()->getPost("files")){
						//基本データに添付ファイル情報を反映
						unset($dat);
						$dat["id"] = $comment_id;
						$dat["attaches"] = json_encode(request()->getPost("files"));
						$this->model("Comments")->write($dat);
					}
					//リダイレクト
					session()->setFlashdata("message","コメントを送信しました。事務局からの回答をお待ち下さい。");
					$this->redirect("/tickets/show/".$crypt_ticket_id);
				}
			}
			return $this->view("/tickets/show","ticket");

		} else {
			$this->redirect("/statics/error");
		}
	}
	function change($section,$ticket_id){
		$this->hasPermission();
		$ticket = $this->model("Tickets")->find($ticket_id);
		if($ticket){
			if($section == "status"){
				unset($dat);
				$dat["id"] = $ticket->id;
				$dat["status"] = request()->getPost("value");
				$this->model("Tickets")->write($dat);
			}
			if($section == "user_id"){
				unset($dat);
				$dat["id"] = $ticket->id;
				$user = $this->model("Users")->find(request()->getPost("value"));
				if($user){
					$dat["user_id"] = request()->getPost("value");
					$dat["group_id"] = $user->group_id;
				}
				$this->model("Tickets")->write($dat);
			}
			$this->library("Api")->success();
		} else {
			$this->library("Api")->error("データが見つかりません");
		}
	}
	function attach($view,$section,$crypt_id,$crypt_no){
		$id = $this->library("Crypt2")->decode($crypt_id);
		$no = $this->library("Crypt2")->decode($crypt_no);
		checkId($id);
		checkId($no);
		$data = false;
		if($section == "ticket"){
			$data = $this->model("Tickets")->find($id);
		}
		if($section == "comment"){
			$data = $this->model("Comments")->find($id);
		}
		if($data){
			$attaches = json_decode($data->attaches);
			if(file_exists(WRITEPATH."files/attach/".$attaches[$no]->save_name)){
				if($view == "output"){
					header('Content-Type: '.$attaches[$no]->mime_type);
					readfile(WRITEPATH."files/attach/".$attaches[$no]->save_name);	
					exit();
				}
			} else {
				$this->redirect("/statics/error");
			}
		} else {
			$this->redirect("/statics/error");
		}
	}
}