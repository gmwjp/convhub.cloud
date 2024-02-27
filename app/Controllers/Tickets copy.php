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
			if(request()->getPost("execute")){
				if($this->model("Comments")->validates("add")){
					
				}
			}
			$this->set("form",$this->model("Forms")->find($ticket->form_id));	
			if($ticket->subform_id){
				$this->set("subform",$this->model("Subforms")->find($ticket->subform_id));	
			}
			$this->set("ticket",$ticket);
			$this->set("ticket_form_items",$this->model("Tickets")->getFormItems($ticket->id));
			$this->set("ticket_subform_items",$this->model("Tickets")->getSubformItems($ticket->id));
			//過去のチケットを取得
			$this->set("old_tickets",$this->model("Tickets")->getOldTickets($ticket->id));
			return $this->view("/tickets/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
}