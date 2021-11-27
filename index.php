<?php

namespace Home;

use Home\Helpers\Helper;
use Home\Views\Categories;

include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Helpers/Helper.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Views/Categories.php');

$helper = new Helper();
$categories = new Categories();
var_dump($helper);
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

  <script src="/dashboard/javascripts/modernizr.js" type="text/javascript"></script>


  <link href="/dashboard/images/favicon.png" rel="icon" type="image/png" />


</head>

<body class="index">
  <?php
  echo $helper->getHeader()
  ?>
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
        <h2>Kategorie</h2>
      </div>
    </div>
    <div class="row">
      <div class="large-12 columns">
        <p>
          Tutaj będzie coś
          <?php 
          $categories->generateViews();
          /*
            echo '<pre>';
            var_dump(file_get_contents(''));
            echo '</pre>'; */
          ?>
        </p>
      </div>
    </div>
  </div>

  <footer>
    <div class="row">
      <div class="large-12 columns">
        <div class="row">
          <div class="large-8 columns">
            <ul class="social">
              <li class="twitter"><a href="https://twitter.com/apachefriends">Follow us on Twitter</a></li>
              <li class="facebook"><a href="https://www.facebook.com/we.are.xampp">Like us on Facebook</a></li>
              <li class="google"><a href="https://plus.google.com/+xampp/posts">Add us to your G+ Circles</a></li>
            </ul>

            <ul class="inline-list">
              <li><a href="https://www.apachefriends.org/blog.html">Blog</a></li>
              <li><a href="https://www.apachefriends.org/privacy_policy.html">Privacy Policy</a></li>
              <li>

              </li>
            </ul>
          </div>
          <div class="large-4 columns">
            <p class="text-right"></p>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- JS Libraries -->
  <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="/dashboard/javascripts/all.js" type="text/javascript"></script>
</body>

</html>