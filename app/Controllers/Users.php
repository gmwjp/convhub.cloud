<?php
namespace App\Controllers;
use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
class Users extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function login(){
		$this->title("ログイン");
		if(request()->getPost("execute")){
			if($this->model("Users")->validates("login")){
				//ログイン処理
				$user = $this->model("Users")->where("mail",request()->getPost("mail2"))->last();
				if($user){
					if(password_verify(request()->getPost("password"), $user->password)) {
						session()->regenerate();
						session()->set("user",$user);
						$this->redirect("/users/dashboard");
					} else {
						// パスワードが間違っている
						$this->set("login_error",true);
						return $this->view("/users/login","member");
					}
				} else {
					$this->set("login_error",true);
					return $this->view("/users/login","member");
				} 	
			} else {
				return $this->view("/users/login","member");
			}
		}
		return $this->view("/users/login","member");
	}
	function signup(){
		$this->title("新規会員登録");
		if(request()->getPost("execute")){
			//仮ユーザーは一旦削除
			$this->model("Users")->where("mail",request()->getPost("mail"))->where("temp",1)->delete();
			if($this->model("Users")->validates("signup")){
				//すでに登録されているか確認
				$checkuser = $this->model("Users")->where("mail",request()->getPost("mail"))->where("temp",0)->last();
				if(!$checkuser){
					//ユーザーがいないので認証メールを送信
					unset($dat);
					$dat["mail"] = request()->getPost("mail");
					$dat["temp"] = 1;
					$user_id = $this->model("Users")->write($dat);
					//メールを送信
					unset($texts);
					$texts["url"] = $_SERVER["SITE_URL"]."/users/signup2/".$this->library("crypt2")->encode($user_id);
					$this->library("SmtpMailer")->send("signup",$texts,request()->getPost("mail"));
					$this->redirect("/users/signup");
				} else {
					//すでにユーザーが存在するのでログインさせる
					session()->setFlashdata("error","すでにこのメールアドレスは会員登録されています。ログインフォームよりログインを行ってください");
					$this->redirect("/users/login");
				}
			} else {
				return $this->view("/users/login");
			}
		} else {
			return $this->view("/users/signup");
		}
	}
	function signup2($crypt_user_id){
		$this->title("新規会員登録");
		$user_id = $this->library("crypt2")->decode($crypt_user_id);
		checkId($user_id);
		$user = $this->model("Users")->find($user_id);
		if($user){
			$this->set("crypt_user_id",$crypt_user_id);			
			//どこの申込みかを確認
			return $this->view("/users/signup2");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function signup2_end($crypt_user_id){
		$this->title("新規会員登録");
		$user_id = $this->library("crypt2")->decode($crypt_user_id);
		checkId($user_id);
		$user = $this->model("Users")->find($user_id);
		if($user){
			$this->set("crypt_user_id",$crypt_user_id);			
			//実行バリデーションチェック
			if(request()->getPost("execute")){
				if($this->model("Users")->validates("signup2")){
					unset($dat);
					$dat["id"] = $user_id;
					$dat["temp"] = 0;
					$dat["password"] = password_hash(request()->getPost("password"),PASSWORD_DEFAULT);
					$this->model("Users")->write($dat);
					//セッション
					session()->set("user",$this->model("Users")->find($user_id));
					$this->redirect("/users/signup2_end/".$crypt_user_id);
				} else {
					return $this->view("/users/signup2");
				}
			} else {
				return $this->view("/users/signup2_end");
			}
			return $this->view("/users/signup2");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function dashboard(){
		$this->title("top");
		$this->hasUsersession();
		return $this->view("/users/dashboard");
	}
}
