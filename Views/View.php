<?php

namespace Home\Views;

class View

{
	const PERCOL = 4;
	const PERROW = 3;
	const QUANTITY = 48;
	private $view = '';

	public function setGrid($content)
	{
		$html = '<div class="row hidden-md-up postsGridWrap">';
		foreach ($content['content'] as $category) :
		$category['url'] = "index.php?" . $content['type'] . "=" . $category['pageId'];
		$html .= '<div class="col-md-' . self::PERCOL .' mb-2 mt-2">
			<div class="posts-card m-auto">
				<a href="' . $category['url'] . '">
					<div class="card-body p-2">
						<h5 class="card-title">' . $category['title'] . '</h5>
					</div>
				</a>
			</div>
		</div>';
		endforeach;
		$html .= '</div>';
		$this->view = $html;
	}

	public function setPage($content)
	{
		$this->view = $content;
	}

	public function renderHTML()
	{
		echo $this->view;
	}

	public function setComments($content, $main = null, $moderator = null)
	{
		$html = '<div id="commentsBox" >
            <div id="makeCommentBox">
                <p class="LebelBasic"></p>
                <input type="text" id="makeCommentText" required minlength="4" maxlength="8" size="300"> <!--Pozmieniać wartości-->
                <div id="makeCommentFiles">
                    <input type="file" id="commentFile1" name="commentFile" accept=".jpg,.jpeg,.png,.gif">
                </div>
                <input type="submit" id="makeCommentSubmit" Value="Comment">
            </div>
            <div id="commentsListBox">
                <ul id="commentList">';
		if ($content) {
			foreach ($content as $comment) {
				$html .= '<li>
								<div class="commentBox">
									<div class="commentCred">
										<p class="commentUsername">' . $comment['eamil'] . '</p>
										<p class="commentDate">' . $comment['timestamp'] . '</p>
									</div>
									<p class="commentText">' . $comment['text'] . '</p>
							</li>';
			}
		}
		$html .= '</ul>
            </div>
        </div>';

		$this->view .= $html;
	}

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
    <li class=""><a href="/mongo/pages/login.php?action=logout"">Logout</a></li>' : '';
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
