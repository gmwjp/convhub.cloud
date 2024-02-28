<?php
namespace App\Models;
class CommentsModel extends _MyModel {
	var $table = "comments";
    var $validate = [
		"add" => [
            "body" => [
				"rules" => 'required|max_length[10000]'
			]
        ]
	];
}
?>