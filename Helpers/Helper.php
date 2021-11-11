<?php

namespace Home\Helpers;

class Helper {

  public function getHeader() {
    return '<div class="contain-to-grid">
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
        <!-- Right Nav Section -->
        <ul class="right">
          <li class=""><a href="/mongo/pages/add-page.php">Add new page</a></li>
          <li class=""><a href="/mongo/pages/login.php">Login</a></li>
          <li class=""><a href="/mongo/pages/sign-in.php">Sign in</a></li>
          <li class=""><a href="/phpmyadmin/">mongoDb</a></li>
        </ul>
      </section>
    </nav>
  </div>';
  }

}
