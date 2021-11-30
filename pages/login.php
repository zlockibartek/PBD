<?php

namespace Home\Pages;

use Home\Collections\User;
use Home\Helpers\Helper;
use Home\Views\View;

include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/User.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Views/View.php');

$user = new User();
$view = new View();

$headerCookies = isset($_COOKIE['header_cookies']) ? $_COOKIE['header_cookies'] : '';
$headerRoles = isset($_COOKIE['roles']) ? $_COOKIE['roles'] : '';
$user->setCookies($headerCookies);
$user->setRoles($headerRoles);

if (isset($_GET['action']) && !$_POST) {
	$user->logOut();
}

if ($_POST) {
	if (!$headerCookies) {
		$user->logIn($_POST['username'], $_POST['password']);
	}
}
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">

	<!-- Always force latest IE rendering engine or request Chrome Frame -->
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- <link href="/dashboard/stylesheets/normalize.css" rel="stylesheet" type="text/css" /> -->
	<link href="/dashboard/stylesheets/all.css" rel="stylesheet" type="text/css" />
	<!-- <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
</head>

<body class="login">

	<div>
		<?= $view->getHeader($user->isLogged(), $user->isModerator()) ?>
		<form action="" name="login" method="POST">
			<div>
				<label for="username">Username:</label>
				<input type="text" id="username" name="username" required>
			</div>
			<div>
				<label for="pass">Password:</label>
				<input type="password" id="pass" name="password" required>
			</div>
			<input type="submit" value="Log in">
		</form>
		<div>
			<a href="/mongo/pages/sign-in.php">Sign in</a>
		</div>
	</div>
</body>

</html>