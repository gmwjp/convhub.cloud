<?php
namespace App\Models;
class GroupsModel extends _MyModel {
	var $table = "groups";
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
}
?>
