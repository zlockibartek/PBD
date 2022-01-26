<?php

namespace Home\Collections;

class Pages
{
	const DEFAULT_COUNT = 21;
	const DEFAULT_OFFSET = 0;
	const API = 'http://10.99.2.20:5000/query/pages';
	protected ?array $categories = null;


	public function getPages($sort = null, $title = null, $offset = null, $count = null)
	{
		$data = json_encode(
			array(
				'title' => $title ?? '',
				'count' => $count ?? self::DEFAULT_COUNT,
				'offset' => $offset ?? self::DEFAULT_OFFSET,
				'sort' => $sort ?? 'popular',
			)
		);

		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json",
				'method'  => 'POST',
				'content' => $data
			)
		);

		// $context = stream_context_create($options);
		// $pages = json_decode(file_get_contents(self::API, false, $context), true);
		// $result = array(
		// 	'content' => $pages,
		// 	'type' => 'page',
		// );

		return [];//$result;
	}
}
