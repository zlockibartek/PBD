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
		ob_flush();
		include($_SERVER['DOCUMENT_ROOT'] . '/mongo/Views/Grid.php');
		$html = ob_get_clean();
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
									<p class="commentText">' . $comment['text'] . '</p>';
									$html .= $moderator ? '<input type="submit" class="deleteComment" Value="Delete">' : '';	
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
