<?php
namespace App\Controllers;
use Algolia\AlgoliaSearch\SearchClient;

class Tickets extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index(){
		$tickets = $this->model("Tickets")->getIndex(request()->getGet());
		$this->set("tickets",$tickets);
		return $this->view("/tickets/index");
	}
	function detail($ticket_id){
		checkId($ticket_id);
		$ticket = $this->model("Tickets")->find($ticket_id);
		if($ticket){

			$this->set("form",$form = $this->model("Forms")->find($ticket->form_id));	
			if($ticket->subform_id){
				$this->set("subform",$this->model("Subforms")->find($ticket->subform_id));	
			}
			$this->set("ticket",$ticket);
			$this->set("ticket_form_items",$this->model("Tickets")->getFormItems($ticket->id));
			$this->set("ticket_subform_items",$this->model("Tickets")->getSubformItems($ticket->id));
			//過去のチケットを取得
			$this->set("old_tickets",$this->model("Tickets")->getOldTickets($ticket->id));
			//コメントを取得
			$this->set("comments",$this->model("Tickets")->getAllComments($ticket->id));

			if(request()->getPost("execute")){
				//バリデーションチェック
				if($this->model("Comments")->validates("add")){
					//データ保存
					request()->addPost("user_section","user");
					request()->addPost("user_id",$this->my_user->id);
					request()->addPost("ticket_id",$ticket->id);
					$this->model("Comments")->write(request()->getPost());
					//問い合わせ主にメール送信
					if(request()->getPost("public_flg") == 1){
						unset($text);
						$text["form_name"] = esc($form->name);
						$text["url"] = "https://".$_SERVER["HTTP_HOST"]."/tickets/show/".$this->library("Crypt2")->encode($ticket->id);
						$this->library("SmtpMailer")->send("send_by_user",$text,$ticket->mail);
					}
					//リダイレクト
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
			$this->title($form->name);
			if($ticket->subform_id){
				$this->set("subform",$this->model("Subforms")->find($ticket->subform_id));	
			}
			$this->set("ticket",$ticket);
			$this->set("ticket_form_items",$this->model("Tickets")->getFormItems($ticket->id));
			$this->set("ticket_subform_items",$this->model("Tickets")->getSubformItems($ticket->id));
			//コメントを取得
			$this->set("comments",$comments = $this->model("Tickets")->getComments($ticket->id));
			if(request()->getPost("execute")){
				//バリデーションチェック
				request()->addPost("user_section","customer");
				request()->addPost("ticket_id",$ticket->id);
				request()->addPost("public_flg",1);
				request()->addPost("status",1);	//対応中に強制切り替え
				if($this->model("Comments")->validates("add")){
					$this->model("Comments")->write(request()->getPost());
					//リダイレクト
					$this->redirect("/tickets/show/".$crypt_ticket_id);
				}
			}
			return $this->view("/tickets/show","ticket");

		} else {
			$this->redirect("/statics/error");
		}

	}
}