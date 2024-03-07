<?php
namespace App\Models;
class TeamsModel extends _MyModel {
	var $table = "teams";
	function getTemplates($team_id){
		return $this->model("Templates")->where("team_id",$team_id)->orderBy("id","desc")->findAll();
	}
	function getForms($team_id){
		return $this->model("Forms")->where("team_id",$team_id)->orderBy("id","desc")->findAll();
	}
	function getWidgets($team_id,$section){
		return $this->model("Widgets")->where("team_id",$team_id)->where("section",$section)->orderBy("id","desc")->findAll();
	}
	function getUsers($team_id){
		return $this->model("Users")->where("team_id",$team_id)->orderBy("id","asc")->findAll();
	}
}
?>
