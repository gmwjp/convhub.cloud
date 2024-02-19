<?php
namespace App\Core;
use CodeIgniter\Log\Handlers\FileHandler as BaseFileHandler;
class BaseLogFileHandler extends BaseFileHandler
{
    public function handle($level, $message): bool
    {
        if (isset($_SERVER["REQUEST_URI"])) {
            $message .= "\n uri -> " . $_SERVER["REQUEST_URI"];
        }
        if (session()->has("user") && session()->get("user")) {
            $message .= "\n user -> " . session()->get("user")->id;
        } else {
            $message .= "\n user -> nologin";
        }
        $message .= "\n method -> ".$_SERVER['REQUEST_METHOD'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = $_POST;
            if (array_key_exists("g-recaptcha-token", $post)) {
                $post["g-recaptcha-token"] = "***";
            }
            if (array_key_exists("code", $post)) {
                $post["code"] = "***";
            }
            if (array_key_exists("passkey", $post)) {
                $post["passkey"] = "***";
            }
            if (array_key_exists("password", $post)) {
                $post["password"] = "***";
            }
            if (array_key_exists("mail", $post)) {
                $post["mail"] = "***";
            }
            $postData = print_r($post, true);
            $message .= "\n post_data -> " . $postData;
        }

        return parent::handle($level, $message);
    }
}
?>