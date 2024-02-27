<?php
namespace App\Models;
class FormsModel extends _MyModel {
	var $table = "forms";
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
        ],
        "input" => [
            "mail" => [
                "rules" => 'required|max_length[255]|valid_email'
            ],
            "title" => [
                "rules" => 'required|max_length[255]'
            ],
            "body" => [
				"rules" => 'required|max_length[10000]'
			]
        ]
	];
    function createValidation($section,$post){
        if($post["names"]){            
            foreach($post["names"] as $key => $val){
                ////POST値の組み立て
                request()->addPost("names_".$key,$post["names"][$key]);
                request()->addPost("select_".$key,$post["select"][$key]);
                if($post["select"][$key] == "radio" || $post["select"][$key] == "checkbox"){
                    request()->addPost("bodies_".$key,$post["bodies"][$key]);
                }
                ////バリデーションの組み立て
                //項目名
                $this->validate[$section]["names_".$key]["rules"] = "required|max_length[64]";
                //選択肢
                if($post["select"][$key] == "radio" || $post["select"][$key] == "checkbox"){
                    $this->validate[$section]["bodies_".$key]["rules"] = "required|max_length[1000]";
                }
            }
        }
    }
	function clearItems($form_id){
        $items = $this->model("FormItems")->where("form_id",$form_id)->where("del_flg",0)->findAll();
        if($items){
            foreach($items as $item){
                unset($dat);
                $dat["id"] = $item->id;
                $dat["del_flg"] = 1;
                $this->model("FormItems")->write($dat);
            }
        }
    }
    function getItems($form_id){
        $items = $this->model("FormItems")->where("form_id",$form_id)->where("del_flg",0)->orderBy("order_no","asc")->findAll();
        return $items;
    }
	function getSubforms($form_id){
		return $this->model("Subforms")->where("form_id",$form_id)->orderBy("order_no","asc")->findAll();
	}
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
