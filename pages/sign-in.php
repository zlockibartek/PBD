<?php

namespace Home\Pages;

use Home\Collections\User;
use Home\DBManager\DBManager;
use Home\Views\View;

include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Views/View.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/User.php');
require_once('C:\xampp\htdocs\mongo\DBManager\DBManager.php');

$user = new User();
$view = new View();
$dbManager = new DBManager();

$headerCookies = isset($_COOKIE['header_cookies']) ? $_COOKIE['header_cookies'] : '';
$headerRoles = isset($_COOKIE['roles']) ? $_COOKIE['roles'] : '';
$user->setCookies($headerCookies);
$user->setRoles($headerRoles);

if ($_POST) {
	$dbManager->register($_POST['username'], $_POST['email'], $_POST['password'], $_POST['firstName']);
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

<body>
	<?= $view->getHeader($user->isLogged(), $user->isModerator()) ?>
	<form action="" name="signIn" method="POST">
		<div>
			<label for="username">Username:</label>
			<input type="text" id="username" name="username" required>
		</div>
		<div>
			<label for="pass">Password (8 characters minimum):</label>
			<input type="password" id="pass" name="password" minlength="8" required>
		</div>
		<div>
			<label for="email">Email:</label>
			<input type="email" id="email" name="email" required>
		</div>
		<div>
			<label for="firstName">Name:</label>
			<input type="text" id="firstName" name="firstName" required>
		</div>
		
		<input type="submit" value="Sign in">
	</form>
	<div>
		<a href="/mongo/pages/login.php">Login</a>
	</div>
</body>

</html>

<?php

