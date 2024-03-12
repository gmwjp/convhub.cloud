<?
//通常一覧取得件数
define("_def_page_num",100);
if(!empty($_FILES)){
	foreach($_FILES as $key => $val){
		request()->addPost($key,$val);
	}
}
if(empty($_SERVER['HTTPS'])){
	$_SERVER["SITE_URL"] = "http://".$_SERVER["HTTP_HOST"];
} else {
	$_SERVER["SITE_URL"] = "https://".$_SERVER["HTTP_HOST"];
}

?>