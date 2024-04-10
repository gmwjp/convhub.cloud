<?php
namespace App\Core;

use CodeIgniter\HTTP\IncomingRequest;

class BaseRequest extends IncomingRequest
{
    function delPost($key){
        // 現在の request()->getPost() 配列を取得
        $currentPostData = $this->getPost();
        unset($currentPostData[$key]);
        $this->setGlobal("post", $currentPostData);
    }
    function addPostFile($key_name, $save_path = WRITEPATH . "uploads"){
        $file_data = false;
    
        $file = $this->getFile($key_name);
        if($file !== null && $file->getSize() != 0 && $file->isValid()){
            // ファイル情報の配列を作成
            $file_data = [
                "file_name" => $file->getName(),
                "file_size" => $file->getSize(),
                "mime_type" => $file->getMimeType(),
            ];
            $new_name = $file->getRandomName();
            $file->move($save_path, $new_name);
            $file_data["save_name"] = $new_name;
        }
    
        if($file_data){
            $this->addPost($key_name, $file_data);
        }    
    }
    function addPostFiles($key_name,$save_path = WRITEPATH."uploads"){
        $files_data = false;

        foreach($this->getFileMultiple($key_name) as $key => $file){
            if($file->getSize() != 0 && $file->isValid()){
                //ファイル情報の配列を作成
                $files_data[$key]["file_name"] = $file->getName();
                $files_data[$key]["file_size"] = $file->getSize();
                $files_data[$key]["mime_type"] = $file->getMimeType();
                $new_name = $file->getRandomName();
                $file->move($save_path,$new_name);
                $files_data[$key]["save_name"] = $new_name;
            }
        }

        if($files_data){
            $this->addPost($key_name,$files_data);
        }    
    }
    function addPost($key,$value){
        $this->addPosts([$key => $value]);
    }
    function addPosts($post){
        $dat = [];
		foreach($post as $key1 => $val1){
            if(!is_array($val1)){   //データが配列ではない場合（例： $_POST["example"]）
                $dat[$key1] = $val1;
            }else{  //データが配列の場合（例： $_POST["example"][n]）
                foreach($val1 as $key2 => $val2)
                $dat[$key1][$key2] = $val2;
            }
		}
        // 現在の request()->getPost() 配列を取得
        $currentPostData = $this->getPost();
        // 新しい値を追加
        if($currentPostData){
            $mergedPostData = $currentPostData;
            foreach ($dat as $key1 => $val1) {
                if(!is_array($val1)){   //データが配列ではない場合（例： $_POST["example"]）
                    //同一のキーがあればそこに上書き、なければ追加される
                    $mergedPostData[$key1] = $val1;
                }else{  //データが配列の場合（例： $_POST["example"][n]）
                    foreach ($val1 as $key2 => $val2) {
                        //同一のキーがあればそこに上書き、なければ追加される
                        $mergedPostData[$key1][$key2] = $val2;
                    }
                }
            }
        }else{
            // POSTデータがまっさらの場合は、データの受け渡しのみ
            $mergedPostData = $dat;
        }
        // request()->getPost() 配列を上書き
        $this->setGlobal('post', $mergedPostData);
    }
    function delGet($key){
        $currentGetData = $this->getGet();
        unset($currentGetData[$key]);
        $this->setGlobal('get',$currentGetData);
    }
    function addGet($key,$value){
        $this->addGets([$key => $value]);
    }
    function addGets($get){
        $dat = [];
		foreach($get as $key => $val){
            $dat[$key] = $val;
		}
        // 現在の request()->getPost() 配列を取得
        $currentGetData = $this->getGet();
        // 新しい値を追加
        $mergedGetData = array_merge($currentGetData, $dat);
        // request()->getPost() 配列を上書き
        $this->setGlobal('get', $mergedGetData);
    }
    function isExecute(){
        return $this->getPost("execute") || $this->getGet("execute");
    }
}
?>