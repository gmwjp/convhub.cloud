<?php
namespace App\Controllers;
use App\Core\BaseController;
abstract class _MyController extends BaseController
{
    function initSystem(){
        parent::initSystem();
		//SSL通信の制御
		if($_SERVER["HTTP_HOST"] != "convhub.cloud"){
			//本番サーバー以外はSSL認証を無視
			$defaultContextOptions = [
				'ssl' => [
					'verify_peer' => false, // これも通常は推奨されませんが、一緒に設定することが多いです
					'verify_peer_name' => false,
					'allow_self_signed' => true
				]
			];
			stream_context_set_default($defaultContextOptions);			
		}		
		//ヘッダ自情報
		if(session()->get("user")){
			$my_user = $this->model("users")->find(session()->get("user")->id);
			if($my_user){
				$this->my_user = $my_user;
				$this->set("my_user",$my_user);
				//未決済注文
			} else {
				session()->destroy();
				$this->redirect("/users/login");
			}
		}
		//メソッド名コントローラー名をセット
		$allSegments = $this->request->getUri()->getSegments();
		$controllerName = isset($allSegments[0]) ? $allSegments[0] : 'UsersController'; // デフォルトのコントローラ名
		$methodName = isset($allSegments[1]) ? $allSegments[1] : 'index'; // デフォルトのメソッド名		
		$this->set("controllerName", $controllerName);
		$this->set("methodName", $methodName);
		//レスポンスヘッダにCSRFを含める
		$response = service('response');
		$response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }
	function title($text){
		$this->set("page_title",$text);
	}
	function hasPermission($str=""){
		if(empty($this->my_user)){
			$this->redirect("/users/login?fromurl=".$_SERVER["REQUEST_URI"]);
		} else {
			if($str != ""){
				if(!in_array($str,explode(",",$this->my_user->auths))){
					$this->redirect("/statics/auth_error");
				}
			}
		}
	}
	public function view($view,$layout = "default"){
        $data['content'] = view($view.request()->getGet("view"), $this->data);
        return view("layouts/".$layout, $data);
    }

}
