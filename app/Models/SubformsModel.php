<?php
namespace App\Models;
class SubformsModel extends _MyModel {
	var $table = "subforms";
	var $validate = [
		"add" => [
			"name" => [
				"rules" => 'required|max_length[64]'
            ],
            "body" => [
				"rules" => 'max_length[10000]'
			],
            "copy_flg" => [
				"rules" => 'numeric'
			]

        ],
        "edit" => [
            "name" => [
                "rules" => 'required|max_length[64]'
            ],
            "body" => [
				"rules" => 'max_length[10000]'
			],
            "copy_flg" => [
				"rules" => 'numeric'
			]

        ]
	];
    function createValidation($section,$post){
        if(!empty($post["names"])){            
            foreach($post["names"] as $key => $val){
                ////POST値の組み立て
                request()->addPost("names_".$key,$post["names"][$key]);
                request()->addPost("select_".$key,$post["select"][$key]);
                request()->addPost("abouts_".$key,$post["abouts"][$key]);
                if($post["select"][$key] == "radio" || $post["select"][$key] == "checkbox"){
                    request()->addPost("bodies_".$key,$post["bodies"][$key]);
                }
                ////バリデーションの組み立て
                //項目名
                $this->validate[$section]["names_".$key]["rules"] = "required|max_length[64]";
                $this->validate[$section]["abouts_".$key]["rules"] = "max_length[255]";
                //選択肢
                if($post["select"][$key] == "radio" || $post["select"][$key] == "checkbox"){
                    $this->validate[$section]["bodies_".$key]["rules"] = "required|max_length[1000]";
                }
            }
        }
    }
    function clearItems($subform_id){
        $items = $this->model("SubformItems")->where("subform_id",$subform_id)->where("del_flg",0)->findAll();
        if($items){
            foreach($items as $item){
                unset($dat);
                $dat["id"] = $item->id;
                $dat["del_flg"] = 1;
                $this->model("SubformItems")->write($dat);
            }
        }
    }
    function getItems($subform_id){
        $items = $this->model("SubformItems")->where("subform_id",$subform_id)->where("del_flg",0)->orderBy("order_no","asc")->findAll();
        return $items;
    }
}
?>
