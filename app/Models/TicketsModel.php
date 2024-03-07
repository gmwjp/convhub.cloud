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
			],
			9 => [
				"text" => "保留",
				"text_mini" => "保",
				"color" => "secondary"
			]
		]
	];
	function getNum($param){
		$this->createParam($param);
		return $this->countAllResults();
	}
	function getIndex($param){
		$this->createParam($param);
		return $this->orderBy("id","desc")->findAll();
	}
	function createParam($param){
		if(!empty($param["user_id"])){
			if($param["user_id"] === "NULL"){
				$this->where("user_id is null");
			} else {
				$this->where("user_id",$param["user_id"]);
			}
		}
		if(!empty($param["status"])){
			$this->where("status",$param["status"]);
		}
		if(!empty($param["status"])){
			$this->where("status",$param["status"]);
		}
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
		return $this->model("Comments")->where("ticket_id",$ticket_id)->orderBy("id","asc")->findAll();
	}
	function getComments($ticket_id){
		return $this->model("Comments")->where("ticket_id",$ticket_id)->where("public_flg",1)->orderBy("id","asc")->findAll();
	}
}
?>
