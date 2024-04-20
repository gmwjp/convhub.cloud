<?php
namespace App\Models;
class PromptsModel extends _MyModel {
	var $table = "prompts";
	var $validate = [
		"add" => [
			"name" => [
				"rules" => 'required|max_length[64]'
            ],
            "body" => [
				"rules" => 'required|max_length[10000]'
			]

        ],
        "edit" => [
            "name" => [
                "rules" => 'required|max_length[64]'
            ],
            "body" => [
				"rules" => 'required|max_length[10000]'
			]
        ]
	];
}
?>
