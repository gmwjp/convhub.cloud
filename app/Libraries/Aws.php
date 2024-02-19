<?
namespace App\Libraries;
use Aws\S3\S3Client;
class Aws {
    function s3exists($fname){
        $file_key = $this->s3path($fname);
        $s3client = \Aws\S3\S3Client::factory([
           'credentials' => [
               'key' => env("s3.key"),
               'secret' => env("s3.secret"),
           ],
           'region' => 'ap-northeast-1',
           'version' => 'latest',
       ]);
        return $s3client->doesObjectExist(env("s3.bucket"), $file_key);
    }
    function s3url($fname){
        return trim("https://".env("s3.bucket")."/".$this->s3path($fname));
    }
    function s3path($fname){
        return $fname;
    }
    function keylist($dir, $filter = false){
        $s3client = \Aws\S3\S3Client::factory([
            'credentials' => [
                'key' => env("s3.key"),
                'secret' => env("s3.secret"),
            ],
            'region' => 'ap-northeast-1',
            'version' => 'latest',
        ]);
        $datas = $s3client->listObjects([
            'Bucket' => env("s3.bucket"),
            'Prefix' => $dir."/"
        ]);
        $results = false;
        foreach ($datas['Contents'] as $data) {
            $fname = str_replace($dir.'/', '', $data["Key"]);
            if(!$fname || ($filter && strpos($fname, $filter) === false)){continue;}
            $results[] = $fname;
        }
        return $results;
    }
    function s3del($fname){
        if($this->s3exists($fname)){
            $file_key = $this->s3path($fname);
    
            $s3client = \Aws\S3\S3Client::factory([
               'credentials' => [
                   'key' => env("s3.key"),
                   'secret' => env("s3.secret"),
               ],
               'region' => 'ap-northeast-1',
               'version' => 'latest',
           ]);
    
           $s3client->deleteObject([
               'Bucket' => env("s3.bucket"),
               'Key'    => $file_key
           ]);
        }
    }
    function s3put($file,$fname){
        $file_key = $this->s3path($fname);
        $s3client = \Aws\S3\S3Client::factory([
            'credentials' => [
                'key' => env("s3.key"),
                'secret' => env("s3.secret"),
            ],
            'region' => 'ap-northeast-1',
            'version' => 'latest',
        ]);
        //アップロードするファイルを用意
        if(file_exists($file)){
            $image = fopen($file,'rb');      
            //画像のアップロード(各項目の説明は後述)
            $result = $s3client->putObject([
                'ACL' => 'private',
                'Bucket' => env("s3.bucket"),
                'Key' => $file_key,
                'Body' => $image,
                'ContentType' => mime_content_type($file),
            ]);
            //読み取り用のパスを返す
            $path = $result['ObjectURL'];
            return $path;
       } else {
           return false;
       }
    }
    function s3put_and_del($file,$fname){
        if(file_exists($file)){
            $this->s3put($file,$fname);
            unlink($file);
        }
    }
    function cf_cache_clear($paths){
        if($paths){
            //CloudFlare：キャッシュクリア
            $zone = env("cloudflare.zone");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".env("cloudflare.zone")."/purge_cache");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            $fields = array();

            $headers = [
                'X-Auth-Email: '.env("cloudflare.mail"),
                'X-Auth-Key: '.env("cloudflare.key"),
                'Content-Type: application/json'
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $data = new \stdClass();
            foreach($paths as $key => $path){
                $data->files[] = ("https://".env("s3.bucket")."".$path);
            }
            //$data->purge_everything = true;
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec ( $ch );
            $result = json_decode($response, true);
            curl_close ( $ch );
        }
    }
}
?>