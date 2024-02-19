<?php

namespace App\Core;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends _MyController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $data;
    protected $db_name = false;


    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        //定数関数を呼び出し
        helper("constants");
        //共通関数を呼び出し
        helper("functions");
        helper("dbug");
        //バリデーションエラーがない場合もとりあえず$errors変数をセットしておく
        $this->set("errors",\Config\Services::validation());

        $this->initSystem();
    }
    //viewに値をセット
    public function set($key,$value){
        $this->data[$key] = $value;
    }
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
        if($this->db_name){
            $this->models[$model]->change_database($this->db_name);
        }
        return $this->models[$model];
    }
    public function view($view,$layout = "default"){
        $data['content'] = view($view, $this->data);
        return view("layouts/".$layout, $data);
    }
    public function redirect($url){
        header('Location: '.$url);
        exit();
    }
    function title($text){
		$this->set("title",$text);
	}
    function change_database($db_name){
        $this->db_name = $db_name;
    }
    function initSystem(){
        
    }
}
