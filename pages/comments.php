<?php

namespace Home\Pages;

use Home\Collections\Comments;
use Home\Collections\User;
use Home\Views\View;

include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/Comments.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/User.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Views/View.php');

$comments = new Comments();
$view = new View();
$user = new User();

$headerCookies = isset($_COOKIE['header_cookies']) ? $_COOKIE['header_cookies'] : '';
$headerRoles = isset($_COOKIE['roles']) ? $_COOKIE['roles'] : '';
$user->setCookies($headerCookies);
$user->setRoles($headerRoles);

?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="/dashboard/stylesheets/all.css" rel="stylesheet" type="text/css" />
	
</head>

<body class="comments">

	<div>
		<?= $view->getHeader($user->isLogged(), $user->isModerator()) ?>
		
		<div>
			<a href="/mongo/pages/sign-in.php">Sign in</a>
		</div>
	</div>
</body>

</html>