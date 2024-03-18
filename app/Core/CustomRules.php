<?php
namespace App\Core;
class CustomRules
{
	public function url($str = ""): bool
    {
        if($str == ""){
            return true;
        } else {
            return (bool) filter_var($str, FILTER_VALIDATE_URL);
        }
    }
	function checkbox_max_check($string, $param){
		if($string == null){
			$count = 0;
		} else {
			$count = count($string);
		}
        if($count > $param){
            return false;
        } else {
            return true;
        }
    }
	//ファイル正常アップロードチェック
	function upload($params){
		if(!empty($params["tmp_name"])){
			if(""!=@$params["name"]){
				if(0 != @$params["error"] ){
					return false ;
				} else {
					return true ;
				}
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
 	function file_kind($string,$param){
		$length = @strlen($string["tmp_name"]);
		if($length == 0){
			return true;
		} else {
			$fname = $string["name"];
			$fdata = pathinfo($fname);
			$filekind = $fdata['extension']; //拡張子
			$kinds = explode(",",$param);
			foreach($kinds as $val){
				if(strtoupper($val) == strtoupper($filekind)){
					return true;
				}
			}
			return false;
		}
	}
	function file_size($string,$param){
		$length = @strlen($string["name"]);
		if($length == 0){
			return true;
		} else {
			if($param < (filesize($string["tmp_name"]) / 1000 / 1000)){
				return false;
			} else {
				return true;
			}
		}
	}
	function file_kind_multiple($files,$param){
		if(@$files){
			foreach ($files as $index => $file) {
				if($file["file_name"] != ""){
					// ファイルが正常にアップロードされたかをチェック
					$find = false;
					foreach(explode(",",$param) as $header){
						if($header == $file["mime_type"]){
							$find = true;
						}
					}
					if($find == false){
						return false;
					}
				}
			}
		} else {
			return true;
		}
		return true;
	}
	function file_size_multiple($files, $maxSizeInKb) {
		$totalSize = 0;
		// ファイル情報が配列として渡されるため、それぞれのファイルサイズを合計する
		if(@$files){
			foreach ($files as $index => $file) {
				$totalSize += $file["file_size"];
			}
			// 合計サイズが最大サイズを超えているかチェック（キロバイト単位）
			if ($totalSize / 1000 / 1000 > $maxSizeInKb) {
				return false; // 合計サイズが最大値を超えている場合はfalseを返す
			} else {
				return true; // 合計サイズが許容範囲内であればtrueを返す
			}	
		} else {
			return true;
		}
	}

	function file_required($string){
		$length = @strlen($string["name"]);
		if($length == 0){
			return false;
		} else {
			return true;
		}
	}
	public function equal(string $str, string $field, array $data = null)
	{
		return isset($data[$field]) && $str === $data[$field];
	}
	function hiragana(string $str): bool
	{
	    if ($str == '')
	    {
	        return TRUE;
	    }
	    $str = mb_convert_encoding($str, 'UTF-8');
	    return ( ! preg_match("/^(?:\xE3\x81[\x81-\xBF]|\xE3\x82[\x80-\x93]|ー)+$/", $str)) ? FALSE : TRUE;
	}
	public function katakana_and_alpha(string $str)
	{
		if ($str === '') return true;
		$str = str_replace([" ", "　"], "", $str);
		return preg_match("/^[a-zA-Zァ-ヶー]+$/u", $str) == 1;
	}
	public function katakana(string $str)
	{
		if ($str === '') return true;
		$str = str_replace([" ", "　"], "", $str);
		return preg_match("/^[ァ-ヶー]+$/u", $str) == 1;
	}
	public function katakana_and_symbol(string $str)
	{
		if ($str === '') return true;
		$str = str_replace([" ", "　"], "", $str);
		return preg_match("/^[ァ-ヶー）（Ａ-Ｚ０-９／（）．]+$/u", $str) == 1;
	}
	function single_katakana(string $str): bool
	{
	    if ($str == '')
	    {
	        return TRUE;
	    }
	    $str = mb_convert_encoding($str, 'UTF-8');
	    return ( ! preg_match("/^(?:\xEF\xBD[\xA1-\xBF]|\xEF\xBE[\x80-\x9F])+$/", $str)) ? FALSE : TRUE;
	}
	function min_after($str,$min){
        if ($str == '')
        {
            return TRUE;
        }
		if(strtotime($str) >= strtotime("+ $min minute")){
			return true;
		} else {
			return false;
		}
    }
    function valid_email(string $str): bool
    {
        if ($str == '')
        {
            return TRUE;
        }
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

	public function in_ngword($string, string $param, array $data): bool
    {

        $params = explode(",", $param);
        foreach ($params as $p) {
            if (strpos(strtolower(trim($string)), strtolower($p)) !== false) {
                return false;
            }
        }
        return true;
    }
	public function in_ngword_regex(string $string = null, string $param): bool
	{
		$params = explode(",", $param);
		foreach ($params as $p) {
			if (preg_match("/" . preg_quote(strtolower($p), '/') . "/", strtolower(trim($string)))) {
				return false;
			}
		}
		return true;
	}
	public function min_price(string $string): bool
	{
		return is_numeric($string) ? (_def_min_price <= $string) : true;
	}
	public function max_price(string $string): bool
	{
		return is_numeric($string) ? (_def_max_price >= $string) : true;
	}
	public function max_price_dl(string $string): bool
	{
		return is_numeric($string) ? (_def_max_price_dl >= $string) : true;
	}
	public function max_support_price(string $string): bool
	{
		return is_numeric($string) ? (_def_max_support_price >= $string) : true;
	}
	public function invalid_special_char(string $str, string $fields = null, array $data = []): bool {
        return strlen($str) === strlen(mb_convert_encoding(mb_convert_encoding($str,'SJIS','UTF-8'),'UTF-8','SJIS'));
    }
    function phone(string $str) : bool
    {
        if ($str == '')
        {
            return TRUE;
        }
        return ( ! preg_match("/\A0[0-9]{9,10}\z/", $str)) ? FALSE : TRUE;
    }

    public function phone_maxlen_11(string $str): bool {
        if ($str === '') {
            return true;
        }
        if (mb_strlen(str_replace("-","",$str)) > 11) {
            return false;
        }
        return preg_match("/^\d{2,5}\-\d{1,4}\-\d{1,4}$/", $str);
    }

    public function post(string $str): bool {
        return ($str === '') || preg_match("/^\d{3}\-\d{4}$/", $str);
    }
	
	public function alpha_numeric_underscore(string $str): bool {
		return ( ! preg_match("/^([a-z0-9_])+$/i", $str)) ? false : true;
	}
	function is_length(string $string, int $num): bool{
		if(is_numeric($string)){
			if($num == strlen($string)){
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
}
?>