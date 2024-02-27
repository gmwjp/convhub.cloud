<?php
namespace App\Models;
class WidgetsModel extends _MyModel {
	var $table = "widgets";
	var $params = [
		"section" => [
			"feedback" => "フィードバック"
		]
	];
	var $validate = [
		"add" => [
			"name" => [
				"rules" => 'required|max_length[64]'
            ]
        ],
        "edit" => [
            "name" => [
                "rules" => 'required|max_length[64]'
            ]
        ]
	];
	function createCode(){
		while(true){
			$temp =  bin2hex(random_bytes(64 / 2));
			if(!$this->where("code",$temp)->last()){
				return $temp;
			}
		}
	}
}
?>
