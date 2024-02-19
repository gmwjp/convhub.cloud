<?
namespace App\Libraries;
class Crypt2 {
    function generate_key(){
        return base64_encode(openssl_random_pseudo_bytes(32));
    }
    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
    function encode($text){
        // 鍵（32バイト = 256ビット）
        $key = base64_decode(env("crypt2.key"));
        // 初期化ベクタ（IV）の生成（aes-256-cbcの場合、16バイトの長さが必要）
        $iv = openssl_random_pseudo_bytes(16);
        // 暗号化
        $ciphertext = openssl_encrypt($text, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        // 暗号文とIVを保存（例として、暗号文とIVをBase64エンコードして連結）
        $combined = base64_encode($ciphertext) . '::' . base64_encode($iv);
        return $this->base64url_encode($combined);
    }
    function decode($combined){
        $combined = $this->base64url_decode($combined);
        // 鍵（32バイト = 256ビット）
        $key = base64_decode(env("crypt2.key"));
        // 暗号文とIVを分解するためのチェック
        $parts = explode('::', $combined);
        if (count($parts) !== 2) {
            // 暗号文の形式が正しくない場合
            return "";
            // throw new \InvalidArgumentException("Invalid encrypted data format.");
        }
        list($ciphertext_base64, $iv_base64) = $parts;
        // Base64デコード
        $ciphertext = base64_decode($ciphertext_base64);
        $iv = base64_decode($iv_base64);
        // 復号化
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($plaintext === false) {
            // 復号化に失敗した場合
            return "";
            // throw new \RuntimeException("Decryption failed. Possibly due to incorrect key or modified data.");
        }
        return $plaintext;
    }
}
?>
