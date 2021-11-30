<?php

namespace Home;

use Home\Helpers\Helper;
use Home\Collections\Categories;
use Home\Collections\Comments;
use Home\Collections\Pages;
use Home\Collections\SinglePage;
use Home\Collections\User;
use Home\Views\View;

include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/Categories.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/Pages.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/Comments.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/SinglePage.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Collections/User.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Views/View.php');

$categories = new Categories();
$comments = new Comments();
$singlePage = new SinglePage();
$pages = new Pages();
$user = new User();
// $file = $_POST ? $_POST['commentFile'] : '';

$headerCookies = isset($_COOKIE['header_cookies']) ? $_COOKIE['header_cookies'] : '';
$headerRoles = isset($_COOKIE['roles']) ? $_COOKIE['roles'] : '';
$user->setCookies($headerCookies);
$user->setRoles($headerRoles);

//keep session somehow
$title = 'Kategorie';
$view = new View();
$commentsView = new View();
$button = isset($_GET['button']) ? $_GET['button'] : null;
$page = isset($_GET['page']) ? $_GET['page'] : '';
if ($_POST) {
  $text = $_POST['send'];
  $file = isset($_FILES['file']) ? $_FILES['file'] : '';
  $fileName = isset($file) && $file['name'] ? $file['name'] : [];
  $user->sendComment($text, $page, $fileName);
  if ($file && $file['name']) {
    echo '<pre>';
    var_dump($file);
    echo '</pre>';
    $user->sendFile($file);
  }
}
if (!$page) {
  $view->setGrid($pages->getPages($button));
} else {
  $content = $singlePage->getPage($page);
  $commentsP = $comments->getComments($page);
  if (!$content) {
    $view->setPage('');
    $title = 'Nie ma takiej strony';
  } else {
    $view->setPage($content['text']);
    $view->setComments($commentsP, null, $user->isModerator());
    $title = $content['title'];
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

  <!-- Use title if it's in the page YAML frontmatter -->
  <title>Welcome to XAMPP</title>

  <meta name="description" content="XAMPP is an easy to install Apache distribution containing MariaDB, PHP and Perl." />
  <meta name="keywords" content="xampp, apache, php, perl, mariadb, open source distribution" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="/dashboard/stylesheets/normalize.css" rel="stylesheet" type="text/css" />
  <link href="/dashboard/stylesheets/all.css" rel="stylesheet" type="text/css" />
  <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="tempsheet.css">
  
  <script src="/dashboard/javascripts/modernizr.js" type="text/javascript"></script>


  <link href="/dashboard/images/favicon.png" rel="icon" type="image/png" />


</head>

<body class="index">
  <?= $view->getHeader($user->isLogged(), $user->isModerator()) ?>
  <div id="wrapper">
    <div class="hero">
      <div class="row">
        <div class="large-12 columns">
          <h1><img src="/mongo/MongoDB_Logo.svg" /> <br>Forum dyskusyjne Wikipedii</span></h1>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="large-12 columns">
        <h2>
          <?= $title ?>
          <!-- <video src="//10.99.2.20:5000/img/kitty.mp4" autoplay poster="posterimage.jpg"> -->
          <?php if (!$page): ?>
          <a href="?button=popular"><button id="latest">Popular</button></a>
          <a href="?button=last"><button id="popular">Latest</button></a>
            <?php endif; ?>
        </h2>
      </div>
    </div>
    <div class="row">
      <div class="large-12 columns">
        <p>
          <?php $view->renderHTML(); ?>
        </p>
      </div>
    </div>
  </div>
  <div>
    <?= $comments->renderAwaitingComments() ?>
  </div>

  <!-- JS Libraries -->
  <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="/dashboard/javascripts/all.js" type="text/javascript"></script>
</body>

</html>