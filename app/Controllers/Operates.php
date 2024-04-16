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
    function export_zendesk($section = "pictspace"){
        $dat = [
            "pictbland" => [
                "domain" => "pictbl-mf-gl",
                "form_id" => 5,
                "subform_id" => 16
            ],
            "pictspace" => [
                "domain" => "pictspace-support",
                "form_id" => 6,
                "subform_id" =>  25
            ],
            "pictsquare" => [
                "domain" => "pictsquare-support",
                "form_id" => 7,
                "subform_id" => 30
            ]
        ];
        $this->library("ZendeskExporter")->exportTickets(
            $dat[$section],
			"rhara@g-m-w.jp",
			env("zendesk.token"),$this
		);
    }
}
?>
