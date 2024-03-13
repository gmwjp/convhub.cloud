<?php
namespace App\Models;
class TicketsModel extends _MyModel {
	var $table = "tickets";
	var $params = [
		"status" => [
			1 => [
				"text" => "未解決",
				"text_mini" => "未",
				"color" => "warning"
			],
			2 => [
				"text" => "解決済",
				"text_mini" => "済",
				"color" => "light"
			]
		]
	];
	function getNum($team_id,$param){
		$this->createParam($team_id,$param);
		return $this->countAllResults();
	}
	function getIndex($team_id,$param){
		$this->createParam($team_id,$param);
		return $this->orderBy("id","desc")->findAll();
	}
	function createParam($team_id,$param){
		$this->select("tickets.*,forms.name forms_name");
		$this->where("tickets.team_id",$team_id);
		if(!empty($param["user_id"])){
			if($param["user_id"] === "NULL"){
				$this->where("tickets.user_id is null");
			} else {
				$this->where("tickets.user_id",$param["user_id"]);
			}
		}
		if(!empty($param["status"])){
			$this->where("tickets.status",$param["status"]);
		}
		if(!empty($param["form_id"])){
			$this->where("tickets.form_id",$param["form_id"]);
		}
		if(!empty($param["keyword"])){
			$param["keyword"] = str_replace("　"," ",$param["keyword"]);
			foreach(explode(" ",$param["keyword"]) as $word){
				$this->groupStart();
				$this->like("tickets.body",$word,"both");
				$this->orLike("tickets.title",$word,"both");
				$this->orLike("tickets.mail",$word,"both");
				$this->groupEnd();
			}
		}
		$this->join("forms","tickets.form_id = forms.id","left");
	}
	function getFormItems($ticket_id){
		return $this->model("TicketFormItems")->where("ticket_id",$ticket_id)->findAll();
	}
	function getSubformItems($ticket_id){
		return $this->model("TicketSubformItems")->where("ticket_id",$ticket_id)->findAll();
	}
	function getOldTickets($ticket_id){
		$ticket = $this->find($ticket_id);
		return $this->where("mail",$ticket->mail)->where("id !=",$ticket_id)->findAll(10);
	}
	function getAllComments($ticket_id){
		return $this->model("Comments")->select("comments.*,users.nickname users_nickname")->join("users","users.id = comments.user_id","left")->where("ticket_id",$ticket_id)->orderBy("id","asc")->findAll();
	}
	function getComments($ticket_id){
		return $this->model("Comments")->where("ticket_id",$ticket_id)->where("public_flg",1)->orderBy("id","asc")->findAll();
	}
}
?>
