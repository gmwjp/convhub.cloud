<?
namespace Config;
use CodeIgniter\Config\BaseConfig;

class ApiConfig extends BaseConfig
{
	public $defines = [];
	public function UriList($base_data=[]) {
		unset($ret);
		if(! is_array($base_data)){
			$ret[] = $base_data;
		}else{
			$ret = $base_data;
		}
		foreach ($this->defines as $api_name => $api_define) {
			foreach ($api_define["methods"] as $method_name => $method_conf) {
				if($method_conf["patterns"]){
					foreach($method_conf["patterns"] as $patten){
						$ret[] = 'api/'.$api_name.'/'.$method_name."/".$patten;
					}	
				} else {
					$ret[] = 'api/'.$api_name.'/'.$method_name;
				}
			}
		}
		return $ret;
	}
}
?>