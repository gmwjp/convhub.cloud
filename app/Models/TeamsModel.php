<?php
namespace App\Models;
class TeamsModel extends _MyModel {
	var $table = "teams";
	function getPrompts($team_id){
		return $this->model("Prompts")->where("team_id",$team_id)->orderBy("order_no","asc")->findAll();
	}
	function getTemplates($team_id){
		return $this->model("Templates")->where("team_id",$team_id)->orderBy("order_no","asc")->findAll();
	}
	function getGroups($team_id){
		return $this->model("Groups")->where("team_id",$team_id)->orderBy("id","desc")->findAll();
	}
	function getForms($team_id){
		return $this->model("Forms")->where("team_id",$team_id)->orderBy("id","desc")->findAll();
	}
	function getSubforms($team_id){
		return $this->model("Subforms")->where("team_id",$team_id)->orderBy("order_no","desc")->findAll();
	}
	function getWidgets($team_id){
		return $this->model("Widgets")->where("team_id",$team_id)->orderBy("id","desc")->findAll();
	}
	function getUsers($team_id){
		return $this->model("Users")->select("users.*,groups.name group_name")->join("groups","users.group_id = groups.id","left")->where("users.team_id",$team_id)->orderBy("users.id","asc")->findAll();
	}
	function getIgnores($team_id){
		return $this->model("Ignores")->where("team_id",$team_id)->orderBy("id","asc")->findAll();
	}
}
?>
