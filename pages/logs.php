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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="/dashboard/stylesheets/all.css" rel="stylesheet" type="text/css" />
	
	
</head>

<body class="logs">

	<div>
		<?= $view->getHeader($user->isLogged(), $user->isModerator());
			$view->renderHTML(); ?>
		<?= 
			$count = 20;
			$offset = 0;
			if($_GET){
				$offset = $count * intval($_GET['page']);
			}
			$view->setAllComments($comments->getAllComments($count, $offset), $offset);
			$view->renderHTML() ?>

		?>
	</div>
</body>

</html>