<?php

namespace Home\Pages;

use Home\Collections\Comments;

include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/Comments.php');

$comments = new Comments();

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
		<?= $helper->getHeader($user->isUser(), $user->isModerator()) ?>
		
		<div>
			<a href="/mongo/pages/sign-in.php">Sign in</a>
		</div>
	</div>
</body>

</html>