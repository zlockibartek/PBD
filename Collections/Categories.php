<?php

namespace Home\Collections;

class Categories
{
	const DEFAULT_CATEGORY = 'Countries_in_Europe';
	const API = 'http://10.99.2.20:5000/wiki-category';
	protected ?array $categories = null;


	public function getCategories($category = null)
	{
		$data = json_encode(
			array(
				'category' => $category ?? self::DEFAULT_CATEGORY,
				'sort' => '',
				'offset' => '',
			));

		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json",
				'method'  => 'POST',
				'content' => $data
			)
		);
		
		$context = stream_context_create($options);
		$result = json_decode(file_get_contents(self::API, false, $context), true);
		$categories = isset($result['categories']) ? $result['categories'] : [];
		$pages = isset($result['pages']) ? $result['pages'] : [];
		$result = [];
		if ($categories) {
			$result['content'] = $categories;
			$result['type'] = 'category';
			$result['separator'] = true;
		}
		else {
			$result['content'] = $pages;
			$result['type'] = 'page';
			$result['separator'] = false;
		}
		
		return $result;

	}

}
