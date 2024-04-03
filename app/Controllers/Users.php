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
		if(!empty($this->my_user)){
			$this->redirect("/tickets/index/my_yet");
		}
		if(request()->getPost("execute")){
			if($this->model("Users")->validates("login")){
				//ログイン処理
				$user = $this->model("Users")->where("mail",request()->getPost("mail2"))->last();
				if($user){
					if(password_verify(request()->getPost("password"), $user->password)) {
						session()->regenerate();
						session()->set("user",$user);
						$this->redirect("/tickets/index/my_yet");
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
	function get_token(){
		helper('form');
		$response = [
			'value'=> csrf_hash()
		];
		return response()->setJSON($response);
	}
	function edit(){
		$this->hasPermission();
		$this->title("プロフィール編集");
		if(!request()->getPost("execute")){
			$user = $this->model("users")->find($this->my_user->id);
			request()->addPost("nickname",$user->nickname);
		} else {
			//バリデートチェック
			if($this->model("users")->validates("edit")){
				//情報を保存
				unset($dat);
				$dat["id"] = $this->my_user->id;
				$dat["nickname"] = request()->getPost("nickname");
				$this->model("users")->write($dat);
				//画像を保存
				session()->setFlashdata("message","プロフィールを編集しました");
				$this->redirect("/users/edit");
			}
		}
		return $this->view("/users/edit");
	}
	function edit_mail(){
		$this->title("メールアドレス設定");
		$this->hasPermission();
		if(request()->getPost("execute")){
			//バリデートチェック
			if($this->model("users")->validates("edit_mail")){
				$exec = false;
				$already_user = $this->model("users")->where("mail",request()->getPost("mail"))->where("id !=",$this->my_user->id)->last();
				if($already_user){
					if($already_user->temp == 1){
						$this->model("users")->del($already_user->id);
						$exec = true;
					} else {
						$this->set("already_user",true);
					}
				} else {
					$exec = true;
				}
				if($exec){
					//認証メールを送信
					$texts["url"] = base_url()."users/edit_mail_exec/".$this->library("Crypt2")->encode($this->my_user->id)."/".$this->library("Crypt2")->encode(request()->getPost("mail"));
					$this->library("SmtpMailer")->send("mail_edit",$texts,request()->getPost("mail"));
					session()->setFlashdata("message","認証メールを送信しました");
					$this->redirect("/users/edit_mail");
				}
			}
		}
		return $this->view("/users/edit_mail");
	}
	function edit_mail_exec($crypt_user_id,$crypt_mail){
		$this->title("メールアドレス変更");
		$this->hasPermission();
		//復号化する
		$param["user_id"] = $this->library("Crypt2")->decode($crypt_user_id);
		$param["mail"] = $this->library("Crypt2")->decode($crypt_mail);
		checkId($param["user_id"]);
		//ユーザーを取得
		$user = $this->model("users")->find($param["user_id"]);
		if($user){
			$this->set("crypt_user_id",$crypt_user_id);
			$this->set("crypt_mail",$crypt_mail);
			//ログイン
			$this->set("uncrypt_mail",$param["mail"]);
			if(request()->isExecute()){
				unset($dat);
				$dat["id"] = $param["user_id"];
				$dat["mail"] = $param["mail"];
				$this->model("users")->write($dat);
				session()->setFlashdata("message","メールアドレスを変更しました");
				$this->redirect("/users/edit_mail");
			}
			return $this->view("/users/edit_mail_exec");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function edit_pass(){
		$this->hasPermission();
		$this->title("パスワード設定");
		if(request()->getPost("execute")){
			//バリデートチェック
			if($this->model("users")->validates("edit_pass")){
				//情報を保存
				unset($dat);
				$dat["id"] = $this->my_user->id;
				$dat["password"] = password_hash(request()->getPost("password"),PASSWORD_DEFAULT);
				$this->model("users")->write($dat);
				session()->setFlashdata("message","パスワードを変更しました");
				$this->redirect("/users/edit_pass");
			}
		}
		return $this->view("/users/edit_pass");
	}
	function detail($id){
		$this->hasPermission();
		$this->title("ユーザー詳細");
		$user = $this->model("Users")->where("id",$id)->where("team_id",$this->my_user->team_id)->last();
		if($user){
			$this->set("user",$user);
			$this->set("group",$this->model("Groups")->find($user->group_id));
			return $this->view("/users/detail");
		} else {
			$this->redirect("/statics/error");
		}
	}
	function manage_edit($user_id){
		$this->title("プロフィール編集");
		$this->hasPermission("user");
		checkId($user_id);
		$user = $this->model("Users")->where("team_id",$this->my_user->team_id)->where("id",$user_id)->last();
		if($user){
			$this->set("user",$user);
			$this->set("groups",$this->model("Teams")->getGroups($user->team_id));
			if(!request()->getPost("execute")){
				$user->auths = explode(",",$user->auths);
				request()->addPosts($user);
			} else {
				//バリデートチェック
				if($this->model("users")->validates("manage_edit")){
					//情報を保存
					unset($dat);
					$dat["id"] = $user_id;
					$dat["nickname"] = request()->getPost("nickname");
					$dat["group_id"] = request()->getPost("group_id");
					$dat["mail"] = request()->getPost("mail");
					if(request()->getPost("auths")){
						$dat["auths"] = implode(",",request()->getPost("auths"));
					} else {
						$dat["auths"] = "";
					}
					$this->model("users")->write($dat);
					//画像を保存
					session()->setFlashdata("message","ユーザーを編集しました");
					$this->redirect("/teams/index");
				}
			}
			return $this->view("/users/manage_edit");
		} else {
			$this->redirect("/statics/error");
		}

	}
	function invite(){
		$this->title("ユーザーをチームに招待する");
		$this->hasPermission("user");
		if(request()->getPost("execute")){
			//バリデートチェック
			if($this->model("users")->validates("invite")){
				//既にユーザーにいないか
				$user = $this->model("Users")->where("mail",request()->getPost("mail"))->last();
				if(!$user){
					//データ保存
					unset($dat);
					$dat["nickname"] = requester_name(request()->getPost("mail"));
					$dat["mail"] = request()->getPost("mail");
					$dat["temp"] = 1;
					$dat["team_id"] = $this->my_user->team_id;
					if(request()->getPost("auths")){
						$dat["auths"] = implode(",",request()->getPost("auths"));
					} else {
						$dat["auths"] = "";
					}
					$user_id = $this->model("Users")->write($dat);
					//招待メールを送信する
					unset($text);
					$text["url"] = $_SERVER["SITE_URL"]."/users/signup2/".$this->library("crypt2")->encode($user_id);
					$this->library("SmtpMailer")->send("invite",$text,request()->getPost("mail"));
					//リダイレクト
					session()->setFlashdata("message","招待メールを送信しました");
					$this->redirect("/teams/index");
				} else {
					$this->set("already_user",true);
				}
			}
		}
		return $this->view("/users/invite");
	}
	function logout(){
		session()->remove("user");
		session()->setFlashdata("message","ログアウトしました");
		$this->redirect("/users/login");
	}
}
