<?php

namespace Home\Collections;

class User
{
	const MODERATOR = 'moderator';
	const USER = 'user';
	const API_LOGIN = 'http://10.99.2.20:5000/users/login';
	const API_LOGOUT = 'http://10.99.2.20:5000/users/login';
	const API_REGISTER = 'http://10.99.2.20:5000/users/login';
	const API_COMMENT = 'http://10.99.2.20:5000/users/comment';
	const API_FILE = 'http://10.99.2.20:5000/users/upload';

	private ?array $roles;
	private ?int $id;
	private $cookie;


	public function __construct($id = null, $roles = array())
	{
		$this->id = $id;
		$this->roles = $roles;
	}

	public function isModerator()
	{
		return in_array(self::MODERATOR, $this->roles);
	}

	public function isUser()
	{
		return in_array(self::USER, $this->roles);
	}

	public function getRoles(): ?array
	{
		return $this->roles;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function logIn($login = null, $password = null)
	{
		$data = json_encode(
			array(
				'email' => 'a@a',
				'password' => 'PH6bGEXDTd6RZ8h',
			)
		);

		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json",
				'method'  => 'POST',
				'content' => $data
			)
		);

		$context = stream_context_create($options);
		$login = json_decode(file_get_contents(self::API_LOGIN, false, $context), true);
		$this->cookies = $http_response_header[5];
		echo '<pre>';
		var_dump($login);
		echo '</pre>';
	}

	public function sendComment($text)
	{
		$data = json_encode(
			array(
				"email" => "a@a",
				"text" => "abcdefghijklm",
				"pageid" => "1",
				"attachements" => ''
			)
		);
		echo '<pre>';
		var_dump($this->cookies);
		echo '</pre>';
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n" .
					"Cookie:" . explode('set-cookie:', $this->cookies)[1] . "\r\n",
				// 'Cookie' => explode('set-cookie:',$this->cookies)[1],
				'method'  => 'POST',
				'content' => $data
			)
		);

		$context = stream_context_create($options);
		$status = json_decode(file_get_contents(self::API_COMMENT, false, $context), true);
		echo '<pre>';
		var_dump($status,);
		echo '</pre>';
	}

	public function sendFile($file)
	{
		define('MULTIPART_BOUNDARY', '--------------------------' . microtime(true));
		$filename = $file['tmp_name'];
		$file_contents = file_get_contents($filename);

		$content =  "--" . MULTIPART_BOUNDARY . "\r\n" .
			"Content-Disposition: form-data; name=\"" . "upfile" . "\"; filename=\"" . $file['name'] . "\"\r\n" .
			"Content-Type: " . $file['type'] . "\r\n\r\n" .
			$file_contents . "\r\n";
		$content .= "--" . MULTIPART_BOUNDARY . "\r\n" .
			"Content-Disposition: form-data; name=\"foo\"\r\n\r\n" .
			"bar\r\n";
		$content .= "--" . MULTIPART_BOUNDARY . "--\r\n";

		$options = array(
			'http' => array(
				'header'  => "Content-type: multipart/form-data; boundary=" . MULTIPART_BOUNDARY . "\r\n" .
					"Cookie:" . explode('set-cookie:', $this->cookies)[1] . "\r\n",
				'method'  => 'POST',
				'content' => $content
			)
		);

		$context = stream_context_create($options);
		$status = json_decode(file_get_contents(self::API_FILE, false, $context), true);
	}
}
