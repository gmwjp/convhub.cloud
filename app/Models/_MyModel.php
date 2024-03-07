<?php
namespace App\Models;
use App\Core\BaseModel;
class _MyModel extends BaseModel {
    function field($table_temp = null){
		if(is_array($table_temp)){
			$f = "";
			foreach($table_temp as $table){
				$temp = $this->query("DESCRIBE ".$table);
				foreach($temp as $key => $te){
					if($f == ""){
						$f .= $table.".".$te->Field." ".$table."_".$te->Field;
					} else {
						$f .= ",".$table.".".$te->Field." ".$table."_".$te->Field;
					}
				}
			}
			return $f;

		} else {
			if($table_temp == null){
				$table = $this->table;
			} else {
				$table = $table_temp;
			}
			$temp = $this->query("DESCRIBE ".$table);
			$f = "";
			foreach($temp as $key => $te){
				if($key == 0){
					$f .= $table.".".$te->Field." ".$table."_".$te->Field;
				} else {
					$f .= ",".$table.".".$te->Field." ".$table."_".$te->Field;
				}
			}
			return $f;
		}
	}
	
	function image_resize($filename, $basename, $w = null, $h = null) {
        if(!file_exists($basename)){
            return false;
        }
        // 画像の元のサイズとタイプを取得
        list($width, $height, $image_type) = getimagesize($basename);
    
        if (is_null($w) && is_null($h)) {
            $new_width = $width;
            $new_height = $height;
        } elseif (is_null($w)) {
            $new_width = ($h / $height) * $width;
            $new_height = $h;
        } elseif (is_null($h)) {
            $new_width = $w;
            $new_height = ($w / $width) * $height;
        } else {
            $ratio = $width / $height;
            $new_width = $w;
            $new_height = $new_width / $ratio;
            if ($new_height > $h) {
                $new_height = $h;
                $new_width = $new_height * $ratio;
            }
        }
    
        // 新しい画像リソースを作成
        $thumb = imagecreatetruecolor($new_width, $new_height);
    
        // PNG画像の場合、透明度情報を保持する設定を行う
        if ($image_type == IMAGETYPE_PNG) {
            imagesavealpha($thumb, true);
            $transparency = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparency);
        }
    
        // 画像の形式に応じて画像を読み込む
        switch ($image_type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($basename);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($basename);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($basename);
                break;
            default:
                return false; // 未対応の形式
        }
    
        // 画像をリサイズ
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
        // $filenameの拡張子を取得
        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
        // 拡張子に応じて保存処理を実行
        switch ($file_extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($thumb, $filename, 100);
                break;
            case 'png':
                imagepng($thumb, $filename,0);
                break;
            case 'gif':
                imagegif($thumb, $filename);
                break;
            default:
                return false; // 未対応の形式
        }
    
        // メモリを解放
        imagedestroy($thumb);
        imagedestroy($source);
    
        return $filename; // リサイズ後のファイル名を返す
    }
}
?>
