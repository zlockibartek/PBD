<?php

namespace Home\Pages;

use Home\Helpers\Helper;

include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Helpers/Helper.php');

$helper = new Helper();
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
	<?= $helper->getHeader() ?>
	<form action="" name="signIn" method="POST">
		<div>
			<label for="username">Username:</label>
			<input type="text" id="username" name="username" required>
		</div>
		<div>
			<label for="pass">Password (8 characters minimum):</label>
			<input type="password" id="pass" name="password" minlength="8" required>
		</div>
		<input type="submit" value="Sign in">
	</form>
	<div>
		<a href="/mongo/pages/login.php">Login</a>
	</div>
</body>

</html>

<?php
$data = $_POST;
if ($_POST) {
	$login = $data['username'];
	$password = $data['password'];
	//validate password?
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
}
//check if user data meets requirements

//if everything is ok, send alert
?>



<?php
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';