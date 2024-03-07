<?php
namespace App\Controllers;
class Statics extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function term(){
		return $this->view("/statics/term");
	}
}
