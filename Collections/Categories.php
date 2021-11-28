<?php

namespace Home\Collections;

class Categories
{
	const DEFAULT_CATEGORY = 0;
	const DEFAULT_COUNT = 48;
	const DEFAULT_OFFSET = 0;
	const API = 'http://10.99.2.20:5000/query/categories';
	const API_PAGE = 'http://10.99.2.20:5000/query/pages';
	protected ?array $categories = null;


	public function getCategories($category = null)
	{
		$data = json_encode(
			array(
				'categoryid' => intval($category),
				'count' => self::DEFAULT_COUNT,
				'offset' => self::DEFAULT_OFFSET,
			)
		);

		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json",
				'method'  => 'POST',
				'content' => $data
			)
		);

		$result = [];
		$context = stream_context_create($options);
		$categories = json_decode(file_get_contents(self::API, false, $context), true);
		
		if ($categories) {
			$result['content'] = $categories;
			$result['type'] = 'category';
		} else {
			$result['type'] = 'page';
			$data = json_encode(
				array(
					'categoryid' => intval($category),
					'count' => self::DEFAULT_COUNT,
					'offset' => self::DEFAULT_OFFSET,
				)
			);

			$options = array(
				'http' => array(
					'header'  => "Content-type: application/json",
					'method'  => 'POST',
					'content' => $data
				)
			);
			$context = stream_context_create($options);
			$categories = json_decode(file_get_contents(self::API_PAGE, false, $context), true);
			$result['content'] = $categories;
		}
		// echo '<pre>';
		

		return $result;
	}
}
