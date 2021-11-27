<?php

namespace Home\Views;

class Categories
{

	const PERCOL = 4;
	const PERROW = 3;
	const QUANTITY = 48;
	const API = 'http://10.99.2.20:5000/wiki-category';
	protected ?array $categories = null;

	public function __construct()
	{
		$this->categories = $this->getCategories();
		// $this->generateViews();

	}

	public function getCategories()
	{
		$data = json_encode(
			array(
				'category' => 'Poland',
				'sort' => '',
				'offset' => '',
				''
			));

		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json",
				'method'  => 'POST',
				'content' => $data
			)
		);
		
		$context = stream_context_create($options);
		$result = json_decode(file_get_contents(self::API, false, $context), true)['categories'];
		return $result;

		
		//   if ($result === FALSE) { }
		// 	$content = file_get_contents(self::API);
		// 	echo '<pre>';
		// 	var_dump(self::API,$content);
		// 	echo '</pre>';
	}

	public function generateViews()
	{
		$html = '<div class="row hidden-md-up postsGridWrap">';
		foreach ($this->categories as $category) {
			$category['title'] = explode(':', $category['title'])[1];
			$category['url'] = "index.php?category=" . str_replace(' ', '_',$category['title']);
			
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
		echo $html;
	}
}
