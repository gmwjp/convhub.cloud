<?php
function csp_script_nonce_test($nonce_only = false) {
    static $nonce = null; //静的関数にノンス保存
    if ($nonce === null) {
        $nonce = base64_encode(random_bytes(16));
    }
    if ($nonce_only){
        return $nonce;
    } else {
        return "nonce='".$nonce."'";
    }
}

function csrf(){
    return csrf_field();
}
function nf($val = ""){
	return number_format((int)$val);
}
function css($filename){
    $modified = date("YmdHis", filemtime(dirname(__FILE__)."/../../public/assets/css/".$filename));
    return '<link rel="stylesheet" type="text/css" href="/assets/css/'.$filename.'?d='.$modified.'">';
}
function js($filename){
    $modified = date("YmdHis", filemtime(dirname(__FILE__)."/../../public/assets/js/".$filename));
    return '<script src="/assets/js/'.$filename.'?d='.$modified.'"></script>';
}
function cutWord($str , $wordnum, $after="...",$enc = 'UTF-8'){
    $ret = strip_tags($str);
    if(mb_strlen($ret,$enc) < $wordnum){
        $after="";
    }
    $ret = mb_substr($ret,0,$wordnum,$enc).$after;
    return $ret;
}
function changeYearOld($value){
    //print $value;
    $today = date('Ymd');
    return floor(($today - str_replace("-","",$value)) / 10000);
}
function changeDate($value,$format = null){
    if($format == null){
        $format = "Y年m月d日 H:i";
    }
    $val = strtotime($value);
    return date($format,$val);
}
function setUrlLink($value){
    $text = preg_replace("{[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]}","<a href=\"\\0\" target=\"_blank\" style=\"text-decoration:underline;\" class=\"noaction\">\\0</a>", $value);
    return $text;
}
function checkId($id = null){
    if($id==null){
        header("Location:/static/error");
        exit();
    }
    if(is_array($id)){
        foreach($id as $val){
            if (preg_match("/^[0-9]+$/", $val)) {
            } else {
                header("Location:/statics/error");
                exit();
            }
        }
        return true;
    } else {
        if (preg_match("/^[0-9]+$/", $id)) {
            return true;
        } else {
            header("Location:/statics/error");
            exit();
        }
    }
}
function mask($value,$top = 2,$under=2){
    // $valueの長さを取得
    $length = mb_strlen($value);

    // $top と $under の合計が$valueの長さ以上の場合、元の値をそのまま返す
    if ($top + $under >= $length) {
        return $value;
    }

    // 先頭と末尾の文字を取得
    $start = mb_substr($value, 0, $top);
    $end = mb_substr($value, -$under);

    // *でマスク
    $masked = str_repeat('*', $length - ($top + $under));

    // 結果を返す
    return $start . $masked . $end;
    
}
function err($error, $prefix = '<div class="mt-1 badge bg-danger p-1 text-white" >', $suffix = '</div>') {
    return $prefix . esc($error) . $suffix;
}
function image_convert_png($filename,$size){
    // Get current dimensions
    list($width, $height) = getimagesize($filename);
    if ($width <= $size && $height <= $size) {
        $newWidth = $width ; 
        $newHeight = $height ; 
    } else {
        if ($width > $height) {
            $newWidth = $size;
            $newHeight = intval($height * $newWidth / $width);
        } else {
            $newHeight = $size;
            $newWidth = intval($width * $newHeight / $height);
        }
    }
    // 元画像を読み込む
    $sourceImage = imagecreatefromstring(file_get_contents($filename));
    $dstImage = imagecreatetruecolor($newWidth, $newHeight);
     // Resize
    imagecopyresampled($dstImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    imagepng($dstImage, $filename,0);
    imagedestroy($sourceImage);
    imagedestroy($dstImage);
}
function image_crop($filename,$new_size = 500){
    // 元画像を読み込む
    $sourceImage = imagecreatefromstring(file_get_contents($filename));
    // 元画像のサイズを取得
    $originalWidth = imagesx($sourceImage);
    $originalHeight = imagesy($sourceImage);
    // 正方形のサイズを決定（元画像の短辺に合わせる）
    $size = min($originalWidth, $originalHeight);
    // 正方形の画像を生成
    $squareImage = imagecreatetruecolor($size, $size);
    // 元画像を正方形にリサイズしてコピー
    imagecopyresampled(
        $squareImage, $sourceImage,
        0, 0,
        ($originalWidth - $size) / 2, // 中央からコピーを始める
        ($originalHeight - $size) / 2,
        $size, $size,
            $size, $size
    );
    // 指定したサイズの新しい画像を生成
    $newImage = imagecreatetruecolor($new_size, $new_size); // この例では100x100にリサイズ
    // 正方形の画像を指定したサイズにリサイズしてコピー
    imagecopyresampled(
        $newImage, $squareImage,
        0, 0,
        0, 0,
        $new_size, $new_size, // この例では100x100にリサイズ
        $size, $size
    );
    // 新しい画像を保存
    imagepng($newImage, $filename,0);

    // メモリを解放
    imagedestroy($sourceImage);
    imagedestroy($squareImage);
    imagedestroy($newImage);
}
function writeLog($val,$level = "info"){
    ob_start(); // Start output buffering
    var_dump($val);
    $result = ob_get_clean(); // Get the contents of the buffer and then clean it
    log_message($level,$result);
}
function deploy_define($def_name){
	return $def_name;
}
