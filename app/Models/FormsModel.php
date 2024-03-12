<?php
namespace App\Models;
class FormsModel extends _MyModel {
	var $table = "forms";
	var $validate = [
		"add" => [
			"name" => [
				"rules" => 'required|max_length[64]'
            ],
            "notion_secret" => [
				"rules" => 'max_length[255]'
            ],
            "body" => [
				"rules" => 'required|max_length[10000]'
			],
            "image" => [
				"rules" => 'file_size[10]|file_kind[jpg,jpeg,gif,png]'
			],
            "url" => [
				"rules" => 'url'
			],
            "notion_url" => [
                "rules" => "url"
            ]
        ],
        "edit" => [
            "name" => [
                "rules" => 'required|max_length[64]'
            ],
            "notion_secret" => [
				"rules" => 'max_length[255]'
            ],
            "body" => [
				"rules" => 'required|max_length[10000]'
			],
            "image" => [
				"rules" => 'file_size[10]|file_kind[jpg,jpeg,gif,png]'
			],
            "url" => [
				"rules" => 'url'
			],
            "notion_url" => [
                "rules" => "url"
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
			],
            "files" => [
                "rules" => "file_size_multiple[10]"
            ]
        ]
	];
    function createValidation($section,$post){
        if(!empty($post["names"])){            
            foreach($post["names"] as $key => $val){
                ////POST値の組み立て
                request()->addPost("names_".$key,$post["names"][$key]);
                request()->addPost("abouts_".$key,$post["abouts"][$key]);
                request()->addPost("select_".$key,$post["select"][$key]);
                if($post["select"][$key] == "radio" || $post["select"][$key] == "checkbox"){
                    request()->addPost("bodies_".$key,$post["bodies"][$key]);
                }
                ////バリデーションの組み立て
                //項目名
                $this->validate[$section]["names_".$key]["rules"] = "required|max_length[64]";
                //説明文
                $this->validate[$section]["abouts_".$key]["rules"] = "max_length[255]";
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
    function getWidgets($form_id){
        return $this->model("Widgets")->where("form_id",$form_id)->orderBy("id","asc")->findAll();
    }
    function createCode(){
		while(true){
			$temp =  bin2hex(random_bytes(64 / 2));
			if(!$this->where("code",$temp)->last()){
				return $temp;
			}
		}
	}
    //よく読まれているウィジェット記事
    function getMostWidgets($form_id){
        // 最初の 30 件のレコードを取得
        $records = $this->model("widgets")->where("form_id", $form_id)->orderBy("view_count", "desc")->findAll(30);
        // 取得したレコードが10件未満の場合は、そのまま全て返す
        if (count($records) <= 10) {
            $randomRecords = $records;
        } else {
            // ランダムにキーを選択
            $randomKeys = array_rand($records, 10);
            // 選択したキーに基づいてレコードを抽出
            $randomRecords = array_map(function($key) use ($records) {
                return $records[$key];
            }, $randomKeys);
        }
        // ランダムに選択されたレコードを返す
        return $randomRecords;    }
}
?>
