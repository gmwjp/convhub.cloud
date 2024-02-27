<?php
namespace App\Models;
class SubformItemsModel extends _MyModel {
	var $table = "subform_items";
	var $params = [
        "required" => [
            0 => "任意",
            1 => "必須"
        ],
        "section" => [
            "textbox" => "１行テキスト",
            "textarea" => "複数行テキスト",
            "radio" => "単一選択",
            "checkbox" => "複数選択"
        ]
    ];
}
?>
