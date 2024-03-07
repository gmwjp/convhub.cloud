<?php
namespace App\Controllers;
class Teams extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function index(){
		$this->title("ユーザー一覧");
		$this->hasPermission();
		$users = $this->model("Teams")->getUsers($this->my_user->team_id);
		$this->set("users",$users);
		return $this->view("/teams/index");
	}
}