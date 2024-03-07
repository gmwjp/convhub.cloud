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
		$ticket_nums["yet"] = $this->model("Tickets")->getNum(["user_id"=>"NULL"]);
		$ticket_nums["my"] = $this->model("Tickets")->getNum(["user_id"=>$this->my_user->id,"status"=>1]);
		$ticket_nums["not_end"] = $this->model("Tickets")->getNum(["status"=>1]);
		$ticket_nums["is_end"] = $this->model("Tickets")->getNum(["status"=>2]);
		$this->set("ticket_nums",$ticket_nums);
		if($section == "yet"){
			request()->addGet("user_id","NULL");
		}
		if($section == "my"){
			request()->addGet("user_id",$this->my_user->id);
			request()->addGet("status",1);
		}
		if($section == "not_end"){
			request()->addGet("status",1);
		}
		if($section == "is_end"){
			request()->addGet("status",2);
		}
		$tickets = $this->model("Tickets")->getIndex(request()->getGet());
		$this->set("tickets",$tickets);
		return $this->view("/tickets/index");
	}
	function detail($ticket_id){
		checkId($ticket_id);
		$ticket = $this->model("Tickets")->find($ticket_id);
		if($ticket){
			$this->title($ticket->title);
			$this->set("users",$this->model("Teams")->getUsers($this->my_user->team_id));
			$this->set("templates",$this->model("Teams")->getTemplates($this->my_user->team_id));
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
					$comment_id = $this->model("Comments")->write(request()->getPost());
					//添付ファイルを処理
					if(request()->getPost("files")["name"][0] !=""){
						$attaches = [];						
						foreach (request()->getPost("files")["name"] as $key=>$file) {
							$save_path = WRITEPATH."files/attach/comment_".$comment_id."_".$key;
							rename(request()->getPost("files")["tmp_name"][$key], $save_path);
							$attaches[$key] = [
								"name" => $file,
								"mime" => mime_content_type($save_path)
							]; 	
						}
						//基本データに添付ファイル情報を反映
						unset($dat);
						$dat["id"] = $comment_id;
						$dat["attaches"] = json_encode($attaches);
						$this->model("Comments")->write($dat);
					}
					//問い合わせ主にメール送信
					if(request()->getPost("public_flg") == 1){
						unset($text);
						$text["form_name"] = esc($form->name);
						$text["url"] = "https://".$_SERVER["HTTP_HOST"]."/tickets/show/".$this->library("Crypt2")->encode($ticket->id);
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
					$comment_id = $this->model("Comments")->write(request()->getPost());
					//添付ファイルを処理
					if(request()->getPost("files")["name"][0] !=""){
						$attaches = [];
						foreach (request()->getPost("files")["name"] as $key=>$file) {
							$save_path = WRITEPATH."files/attach/comment_".$comment_id."_".$key;
							rename(request()->getPost("files")["tmp_name"][$key], $save_path);
							$attaches[$key] = [
								"name" => $file,
								"mime" => mime_content_type($save_path)
							]; 
						}
						//基本データに添付ファイル情報を反映
						unset($dat);
						$dat["id"] = $comment_id;
						$dat["attaches"] = json_encode($attaches);
						$this->model("Comments")->write($dat);
					}
					//リダイレクト
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
				$dat["user_id"] = request()->getPost("value");
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
			if(file_exists(WRITEPATH."files/attach/{$section}_{$id}_{$no}")){
				if($view == "output"){
					header('Content-Type: '.$attaches[$no]->mime);
					readfile(WRITEPATH."files/attach/{$section}_{$id}_{$no}");	
					exit();
				}
				if($view == "view"){
					$this->set("section",$section);
					$this->set("data",$data);
					$this->set("file",$attaches[$no]);
					$this->view("/tickets/attach","ajax");
				}
			} else {
				$this->redirect("/statics/error");
			}
		} else {
			$this->redirect("/statics/error");
		}
	}
}