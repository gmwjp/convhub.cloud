<?php
namespace App\Models;
class UsersModel extends _MyModel {
	var $table = "users";
	var $validate = [
		"signup" => [
			"mail" => [
				"rules" => 'required|valid_email|is_unique[users.mail]',
				"messages" => [
					"is_unique" => "登録済みのメールアドレスです"
				]
			]
		],
		"signup2" => [
			"term_check" => [
				"rules" => 'required'
			],
			"password" => [
				"rules" => 'min_length[4]|max_length[32]|regex_match[/^[a-zA-Z0-9!#$%&()*+,.:;=?@^_|~ -]+$/]'
			],
			"password_confirm" => [
				"rules" => 'required|matches[password]',
				"matches" => [
					"is_unique" => "パスワードに相違があります"
				]
			],			
		],
		"login" => [
			"mail2" => [
				"rules" => 'required|valid_email'
			],
			"password" => [
				"rules" => 'required'
			]			
		],
		"edit" => [
			"nickname" => [
				"rules" => 'required|max_length[32]'
			]			
		],
		"edit_mail" => [
			"mail" => [
				"rules" => 'required|valid_email'
			]			
		],
		"edit_pass" => [
			"password" => [
				"rules" => 'min_length[4]|max_length[32]|regex_match[/^[a-zA-Z0-9!#$%&()*+,.:;=?@^_|~ -]+$/]'
			],
			"password_confirm" => [
				"rules" => 'required|matches[password]',
				"matches" => [
					"is_unique" => "パスワードに相違があります"
				]
			],			
		],
		"manage_edit" => [
			"nickname" => [
				"rules" => 'required|max_length[32]'
			],
			"mail" => [
				"rules" => 'required|valid_email'
			]
		],
		"invite" => [
			"nickname" => [
				"rules" => 'required|max_length[32]'
			],
			"mail" => [
				"rules" => 'required|valid_email'
			]
		]
	];
}
?>
