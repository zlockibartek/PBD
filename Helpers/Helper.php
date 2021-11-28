<?php

namespace Home\Helpers;

class Helper
{

  public function getHeader($user = null, $moderator = null)
  {
    $html = '<div class="contain-to-grid">
    <nav class="top-bar" data-topbar>
      <ul class="title-area">
        <li class="name">
          <h1><a href="/mongo/index.php">Menu</a></h1>
        </li>
        <li class="toggle-topbar menu-icon">
          <a href="#">
            <span>Menu</span>
          </a>
        </li>
      </ul>
      <section class="top-bar-section">
      <ul class="right">';
    $html .= $user ? '<li class=""><a href="/mongo/pages/add-page.php">Add new page</a></li>
    <li class=""><a href="/phpmyadmin/">Logout</a></li>' : '';
    $html .= !$user ? '<li class=""><a href="/mongo/pages/login.php">Login</a></li>
      <li class=""><a href="/mongo/pages/sign-in.php">Sign in</a></li>' : '';
    $html .= $moderator ? '<li class=""><a href="/mongo/pages/comments.php">Comments</a></li>' : '';
    $html .= '</ul>
      </section>
      </nav>
      </div>';
    return $html;
  }
}
