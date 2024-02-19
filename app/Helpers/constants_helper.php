<?

//通常一覧取得件数
define("_def_page_num",20);
if(!empty($_FILES)){
	foreach($_FILES as $key => $val){
		request()->addPost($key,$val);
	}
}
//イベントを探す一覧取得件数
define("_def_events_page_num",100);
if(!empty($_FILES)){
	foreach($_FILES as $key => $val){
		request()->addPost($key ,$val);
	}
}
//何ヶ月前から次年度の受付を開始するか（期首：４月）
define("_def_prev_signup_month","1");

if(empty($_SERVER['HTTPS'])){
	$_SERVER["SITE_URL"] = "http://".$_SERVER["HTTP_HOST"];
} else {
	$_SERVER["SITE_URL"] = "https://".$_SERVER["HTTP_HOST"];
}
define("_def_index",100);

?>