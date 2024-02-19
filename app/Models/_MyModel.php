<?php
namespace App\Models;
use App\Core\BaseModel;
class _MyModel extends BaseModel {
    public function escape($string){
        // ここでDB接続を取得
        $db = \Config\Database::connect();
        $mysqli = $db->mysqli;

        // mysqli_real_escape_stringを使用
        return mysqli_real_escape_string($mysqli, $string);
    }
    function mergeOldValidate($old_ruleName){
        foreach($this->$old_ruleName as $val){
            if($val["rules"] !=""){
                $this->validate[$old_ruleName][$val["field"]] = [
                    "rules" => $val["rules"],
                    "label" => $val["label"]
                ];

            }
        }
    }
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
    function oldValidates($data){
        $this->mergeOldValidate("validate");
        return $this->validates("validate",$data);
    }
}
?>
