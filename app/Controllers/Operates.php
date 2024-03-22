<?php
namespace App\Controllers;
class Operates extends _MyController {
    function initSystem() {
        parent::initSystem();
    }
    //なりすましログイン
	function login($id){
		$user = $this->model("users")->find($id);
		if($user){
			session()->set("user",$user);
			$this->redirect("/tickets/index/my_yet");
		} else {
			$this->redirect("/statics/error");
		}
	}
    function pull(){
        $command = "sudo /root/pull.sh 2>&1";
        $output = [];
        $return_var = 0;
        exec($command, $output, $return_var);

        if ($return_var !== 0) {
            echo "Error occurred: $return_var <br>";
        }    

        foreach ($output as $line) {
            echo "<pre>$line</pre>";
        }
        // それぞれの結果を出力
    }
}
?>
