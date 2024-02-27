<?php
namespace App\Models;
class TicketsModel extends _MyModel {
	var $table = "tickets";
	var $params = [
		"status" => [
			0 => [
				"text" => "未対応",
				"text_mini" => "未",
				"color" => "danger"
			],
			1 => [
				"text" => "対応中",
				"text_mini" => "中",
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
	function getIndex($param){
		return $this->orderBy("id","desc")->findAll(0,100);
	}
	function getFormItems($ticket_id){
		return $this->model("TicketFormItems")->where("ticket_id",$ticket_id)->findAll();
	}
	function getSubformItems($ticket_id){
		return $this->model("TicketSubformItems")->where("ticket_id",$ticket_id)->findAll();
	}
	function getOldTickets($ticket_id){
		$ticket = $this->find($ticket_id);
		return $this->where("mail",$ticket->mail)->findAll(10);
	}
	function getAllComments($ticket_id){
		return $this->model("Comments")->where("ticket_id",$ticket_id)->orderBy("id","asc")->findAll();
	}
	function getComments($ticket_id){
		return $this->model("Comments")->where("ticket_id",$ticket_id)->where("public_flg",1)->orderBy("id","asc")->findAll();
	}
}
?>
