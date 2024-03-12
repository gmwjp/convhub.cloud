<?php
namespace App\Models;
class IgnoresModel extends _MyModel {
	var $table = "ignores";
	var $validate = [
		"add" => [
			"url" => [
				"rules" => 'required|url'
            ]
        ]
	];
}
?>
