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
		foreach ($content['content'] as $category) {
			$category['title'] = $content['separator'] ? explode(':', $category['title'])[1] : $category['title'];
			$category['url'] = "index.php?" . $content['type'] . "=" . str_replace(' ', '_',$category['title']);
			$html .= '<div class="col-md-' . self::PERCOL . ' mb-2 mt-2">
						<div class="posts-card m-auto">
							<a href="' . $category['url'] . '">
							<div class="card-body p-2">
								<h5 class="card-title">' . $category['title'] . '</h5>
							</div>
							</a>
						</div>
					</div>';
		}
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
}
