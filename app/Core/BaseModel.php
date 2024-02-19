<?php 
namespace App\Core;

use CodeIgniter\Model;
use Config\Services;

class BaseModel extends Model
{
	protected $returnType = 'object'; 
	var $models = [];
	var $library = [];
	public function change_database($db_name){
		$this->db = \Config\Database::connect($db_name);
	}
	function e($val){
		$mysqli = $this->db->getMysqli();
		return $mysqli->real_escape_string($val);
	}
    public function query($sql,$keys = null){
        return $this->db->query($sql,$keys)->getResult();
    }
	public function library($name){
		$name = ucfirst($name);
        if(empty($this->library[$name])){
            $libName = "\App\Libraries\\$name";
            $this->library[$name] = new $libName();
        }
        return $this->library[$name];
    }
	function getSql(){
		return $this->builder()->getCompiledSelect();
	}
	public function model($model){
        //スネーク形式の命名か？
        if(strpos($model,"_") !== false){
            $temp = explode("_",$model);
            //キャメル名に変更
            $new_name = "";
            for($i = 0 ; $i < count($temp) ; $i ++){
                $new_name .= ucfirst($temp[$i]);
            }
            $model = $new_name;

        }

        if(empty($this->models[$model])){
            $model = ucfirst($model);
            $modelName = "App\Models\\$model"."Model";
            $this->models[$model] = model($modelName);
        }
        return $this->models[$model];
    }
	function fields($table_temp = null){
		if($table_temp == null){
			$tables[] = $this->table;
		} else {
			if(is_array($table_temp)){
				$tables = $table_temp;
			} else {
				$tables[] = $table_temp;
			}
		}
		$f = "";
		foreach($tables as $table){
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
	}
	function last(){
		return $this->orderBy("id","desc")->first();
	}
	/*
    public function find($id){
        $sql = "select * from $this->table where id = $id";
        $data = $this->query($sql);
        if($data){
            return $data[0];
        } else {
            return false;
        }
    }

	*/
    public function findByQuery($query){
        $sql = "select * from $this->table where 1 = 1 ";
        $keys = array();
		if(is_array($query)){
			foreach($query as $key => $val){
				$sql.= " and ".$key."= ? ";
				$keys[] = $val;
			}	
		} else {
			$sql.= " and ".$query;
		}
        $data = $this->query($sql,$keys);
        if($data){
            return $data[0];
        } else {
            return false;
        }
    }
    public function write($val = false) : int {
        if($val == false){
			$val = request()->getPost();
		}
		if(empty($this->db_fields)){
			//フィールドリストを取得
			$sql = "select * from information_schema.columns c where c.table_schema = '".$this->db->database."' and c.table_name   = '".$this->table."' order by ordinal_position";
			$this->db_fields = $this->query($sql);
		}
		if(empty($val["id"])){
			if(empty($val["created"])){
				$val["created"] = date("Y-m-d H:i:s");
			}
			if(empty($val["modified"])){
				$val["modified"] = date("Y-m-d H:i:s");
			}
			foreach($val as $key => $v){
				$find = false;
				foreach($this->db_fields as $field){
					if($field->COLUMN_NAME ==$key){
						$find = true;
					}
				}
				if($find == false){
					unset($val[$key]);
				}
			}
			$this->db->table($this->table)->insert($val);
            return $this->db->insertID();
		} else {
			$target_id = $val["id"];
			unset($val["id"]);
			if(empty($val["modified"])){
				$val["modified"] = date("Y-m-d H:i:s");
			}
			foreach($val as $key => $v){
				$find = false;
				foreach($this->db_fields as $field){
					if($field->COLUMN_NAME ==$key){
						$find = true;
					}
				}
				if($find == false){
					unset($val[$key]);
				}
			}
            $this->db->table($this->table)->update($val,["id"=>$target_id]);
			$sql = $this->db->getLastQuery();
            return $target_id;
		}
	}
	function del($id){
		$this->where("id",$id)->delete();
	}
	function getCount(){
		$temp = $this->query("SELECT FOUND_ROWS() row");
		if($temp){
			return $temp[0]->row;
		} else {
			return 0;
		}
	}
	function getMaxId(){
		$sql = "select id from {$this->table} order by id desc limit 1";
		$temp = $this->query($sql);
		return $temp[0]->id;
	}
	function getResult($sql){
		$temp = $this->query($sql);
		$ret["data"] = $temp;
		$ret["count"] = $this->getCount();
		return $ret;
	}
	function createLimit(){
		return " limit ".($this->library("pagenate")->getPage()-1)*_def_listnum.","._def_listnum;
	}
	function validates($ruleGroup = "default",$data = false){
		if($data == false ){
			$data = request()->getPost();
		}

		$validation = Services::validation();
		$validation->reset();
		//バリデーションをセット
		foreach($this->validate[$ruleGroup] as $field => $val){
			if(empty($val["messages"])){
				$validation->setRule($field,@$val["label"],$val["rules"]);	
			} else {
				$validation->setRule($field,@$val["label"],$val["rules"],@$val["messages"]);	
			}
		}
		return $validation->run($data);
	}
	function validates_and_write($ruleGroup = "default",$data = false){
		if($data == false ){
			$data = request()->getPost();
		}
		if($this->validates($ruleGroup,$data)){
			$id = $this->write();
			return $id;
		} else {
			return false;
		}
	}
	public function execute($sql,$keys = null){
        return $this->db->query($sql,$keys);
    }

	function addRule($ruleGroup,$field,$add_rule,$message = false){
		$rules = explode("|",$this->validate[$ruleGroup][$field]["rules"]);
		$rules[] = $add_rule;
		$this->validate[$ruleGroup][$field]["rules"] = implode("|",$rules);
		if($message){
			@$this->validate[$ruleGroup][$field]["messages"][$add_rule] = $message;
		} else {
			unset($this->validate[$ruleGroup][$field]["messages"][$add_rule]);
		}
	}
	function removeRule($ruleGroup,$field,$remove_rule = false){
		$rules = explode("|",$this->validate[$ruleGroup][$field]["rules"]);
		foreach($rules as $key => $rule){
			if($rule == $remove_rule || $remove_rule == false){
				unset($rules[$key]);
			}
		}
		$this->validate[$ruleGroup][$field]["rules"] = implode("|",$rules);
	}
	function removeField($ruleGroup,$field){
		unset($this->validate[$ruleGroup][$field]);
	}
}