<?
namespace App\Core;

use CodeIgniter\View\View;

class BaseView extends View {
    // Add your new methods or override existing ones
    public function library($name){     
        $name = ucfirst($name);
        if(empty($this->library[$name])){
            $libName = "\App\Libraries\\$name";
            $this->library[$name] = new $libName();
        }
        return $this->library[$name];
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
    public function element($fname,$arg = []){
        return view("elements/".$fname,$arg);
    }
}
?>