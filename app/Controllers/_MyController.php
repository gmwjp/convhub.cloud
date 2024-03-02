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
		//APIアクセスの接続元確認
		$this->checkApiRemoteAddress();
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
	function hasUserSession(){
		if(empty($this->my_user)){
			$this->redirect("/users/login?fromurl=".$_SERVER["REQUEST_URI"]);
		}
	}
	public function view($view,$layout = "default"){
        $data['content'] = view($view.request()->getGet("view"), $this->data);
        return view("layouts/".$layout, $data);
    }
	//アクセス元のREMOTE_ADDR確認
	function checkApiRemoteAddress(){
		if(strpos($_SERVER["REQUEST_URI"],"/api/") !== false){	//apiでのアクセス時のみチェックする
			//キーを決定
			$ip = "";
			if (isset($_SERVER["HTTP_X_REAL_IP"])) {
				$ip = $_SERVER["HTTP_X_REAL_IP"];
			} else {
				$ip = $_SERVER["REMOTE_ADDR"];
			}
			$ret = false;
			for($i=1; $i<=7; $i++){
				// 複数のプロダクトからアクセスされることを想定
				if($ip != ""){
					if(env('remote.addr.'.$i) === $ip || $ip == "::1"){	//自分自身も許可
						$ret = true;
						break;
					}	
				}
			}
			if(!$ret){
				//API専用のエラー出力
				$this->library("api")->error("アクセスエラー");
				exit();	//エラーの場合はここで処理終了する
			}			
		}
	}
}
