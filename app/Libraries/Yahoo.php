<?
namespace App\Libraries;

class Yahoo {
    function splitWord($query) {
        $appId = "dj00aiZpPVBMdGFNcmNZbTV4QyZzPWNvbnN1bWVyc2VjcmV0Jng9ZjA-"; // <-- ここにあなたのClient ID（アプリケーションID）を設定してください。
        $url = "https://jlp.yahooapis.jp/MAService/V2/parse";
        
        $headers = [
            "Content-Type: application/json",
            "User-Agent: Yahoo AppID: {$appId}",
        ];
        
        $paramDic = [
            "id" => "1234-1",
            "jsonrpc" => "2.0",
            "method" => "jlp.maservice.parse",
            "params" => [
                "q" => $query,
            ],
        ];
        $params = json_encode($paramDic);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
    function getMeishi($query){
        $result = json_decode($this->splitWord($query));
        //dbug($result);
        $ret = false;
        foreach($result->result->tokens as $val){
            if($val[3] == "名詞" || $val[3] == "未定義語"){
                $ret[] = $val[0];
            }
        }
        return $ret;
    }
}
?>