<?php

namespace Home\Collections;

class User
{
	const MODERATOR = 'moderator';
	const USER = 'user';
	const API_LOGIN = 'http://10.99.2.20:5000/users/login';
	const API_PAGE = 'http://10.99.2.20:5000/users/page';
	const API_LOGOUT = 'http://10.99.2.20:5000/users/logout';
	const API_REGISTER = 'http://10.99.2.20:5000/users/register';
	const API_COMMENT = 'http://10.99.2.20:5000/users/comment';
	const API_FILE = 'http://10.99.2.20:5000/users/upload';
	const API_REMOVE = 'http://10.99.2.20:5000/users/deletecomment';
	const API_LOGS = 'http://10.99.2.20:5000/users/logs';

	private string $roles;
	private ?int $id;
	private $cookies;


	public function __construct($id = null, $roles = '')
	{
		$this->id = $id;
		$this->roles = $roles;
	}

	public function logIn($login = '', $password = '')
	{
		$data = json_encode(
			array(
				'email' => $login,
				'password' => $password,
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
		if ($login) {
			$this->cookies = $http_response_header[5];
			$this->roles = $login[0];
			setcookie('header_cookies', $http_response_header[5], time() + 3600, '/');
			setcookie('roles', $this->roles, time() + 3600, '/');
		}
	}

	public function register($login, $password)
	{
		$data = json_encode(
			array(
				'email' => $login,
				'password' => $password,
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
		$login = json_decode(file_get_contents(self::API_REGISTER, false, $context), true);
	}

	public function logOut()
	{
		$this->cookies = $this->cookies ? explode('set-cookie:', $this->cookies)[1] : '';
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n" .
					"Cookie:" . $this->cookies . "\r\n",
				'method'  => 'POST',
				'content' => ''
			)
		);
		$context = stream_context_create($options);
		$logout = json_decode(file_get_contents(self::API_LOGOUT, false, $context), true);
		$this->cookies = '';
		$this->roles = '';
		setcookie('header_cookies', '', '1', '/');
		setcookie('roles', '', '1', '/');
	}

	public function sendComment($text, $pageId, $attachment)
	{
		$data = json_encode(
			array(
				"text" => $text,
				"pageid" => intval($pageId),
				"attachements" => is_array($attachment) ? $attachment : [$attachment],
			)
		);
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n" .
					"Cookie:" . explode('set-cookie:', $this->cookies)[1] . "\r\n",
				'method'  => 'POST',
				'content' => $data
			)
		);

		$context = stream_context_create($options);
		$status = json_decode(file_get_contents(self::API_COMMENT, false, $context), true);
		echo '<pre>';
		var_dump($status);
		echo '</pre>';
	}

	public function sendPage($title)
	{
		$data = json_encode(
			array(
				'title' => $title,
			)
		);
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n" .
					"Cookie:" . explode('set-cookie:', $this->cookies)[1] . "\r\n",
				'method'  => 'POST',
				'content' => $data
			)
		);

		$context = stream_context_create($options);
		$status = json_decode(file_get_contents(self::API_PAGE, false, $context), true);
	}

	public function removeComment($timestamp)
	{
		$data = json_encode(
			array(
				'timestamp' => intval($timestamp),
			)
		);
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n" .
					"Cookie:" . explode('set-cookie:', $this->cookies)[1] . "\r\n",
				'method'  => 'POST',
				'content' => $data
			)
		);

		$context = stream_context_create($options);
		$status = json_decode(file_get_contents(self::API_REMOVE, false, $context), true);
	}

	public function getLogs()
	{
		$data = json_encode(
			array(
				'count' => 10,
			)
		);
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n" .
					"Cookie:" . explode('set-cookie:', $this->cookies)[1] . "\r\n",
				'method'  => 'POST',
				'content' => $data
			)
		);
		$context = stream_context_create($options);
		$status = json_decode(file_get_contents(self::API_LOGS, false, $context), true);
		return $status;
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
		file_get_contents(self::API_FILE, false, $context);
	}

	public function isModerator()
	{
		return self::MODERATOR == $this->roles;
	}

	public function isUser()
	{
		return self::USER == $this->roles;
	}

	public function isLogged()
	{
		return $this->roles;
	}

	public function getRoles(): string
	{
		return $this->roles;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getCookies()
	{
		return $this->cookies;
	}

	public function setCookies($cookies): self
	{
		$this->cookies = $cookies;

		return $this;
	}

	public function setRoles(string $roles): self
	{
		$this->roles = $roles;

		return $this;
	}
}
