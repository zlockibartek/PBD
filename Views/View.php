<?php

namespace Home\Views;

use DateTime;

class View

{
	const PERCOL = 4;
	const PERROW = 3;
	const QUANTITY = 48;
	const SERVER = '//10.99.2.20:5000/img/';
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
			<form method="POST" enctype="multipart/form-data">
                <p class="LebelBasic"></p>
                <input type="text" id="makeCommentText" name="send" required minlength="4" size="300"> <!--Pozmieniać wartości-->
                <div id="makeCommentFiles">
                    <input type="file" id="commentFile1" name="file" accept=".jpg,.jpeg,.png,.gif">
                </div>
                <input type="submit" id="makeCommentSubmit" Value="Comment">
				</form>
            </div>
            <div id="commentsListBox">
                <ul id="commentList">';
		if ($content) {

			foreach ($content as $comment) {
				$file = isset($comment['attachements']) && $comment['attachements'] ? self::SERVER . $comment['attachements'][0] : '';
				$html .= '<li>
								<div class="commentBox">
									<div class="commentCred">
										<p class="commentUsername">' . $comment['eamil'] . '</p>
										<p class="commentDate">' . date('Y-m-d H:i:s', $comment['timestamp']/1000) . '</p>
									</div>
									<p class="commentText">' . $comment['text'] . '</p>';
									$html .= '<img style="max-width: 100px; max-height: 100px;" src=' . $file . '>';
									$html .= $moderator ? '<a href="' . $_SERVER['REQUEST_URI'] . '&remove=' . $comment['timestamp'] . '"><button>Delete</button></a>' : '';	
							$html .= '</li>';
			}
		}
		$html .= '</ul>
            </div>
        </div>';

		$this->view .= $html;
	}

	public function setAllComments($content, $iter = 0)
	{
		$html = '<div style="width: 80vw; margin: auto">
			<table class="table">
			<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">User</th>
				<th scope="col">Content</th>
				<th scope="col">Page ID</th>
				<th scope="col">Action</th>
			</tr>
			</thead>
			<tbody>
			<tr>';
		foreach ($content as $comment){
			$html .= '<th scope="row">' . ++$iter . '</th>
			<td style="width: 20%">' . $comment['eamil'] . '</td>
			<td style="width: 100%"> <p>Text:' . $comment['text'] . '</p>
				<p>Attachment:' . $this->displayAttachment($comment['attachment']). '</td>
			<td>' . $comment['pageiD'] . '</td>
			<td> <input type="submit" class="acceptCommentAll" Value="Accept"> <input type="submit" class="deleteCommentAll" Value="Delete"> </td>

		  </tr>';
		}
		$html .= '</tbody>
	  	</table>
		</div>';

		$this->view = $html;
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
	private function displayAttachment($source)
	{
		if($source){
			if(mime_content_type($source)== "image/gif"){
				return '<img style="max-width: 100px; max-height: 100px;" src=' . $source . '/>';
			}
			return '<p>' . $source . '</p>';
		}
		return '';
	}
}
