<?php
namespace App\Models;
class TemplatesModel extends _MyModel {
	var $table = "templates";
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
