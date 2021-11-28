<?php

namespace Home\Collections;

class Pages
{
	const DEFAULT_COUNT = 50;
	const DEFAULT_OFFSET = 0;
	const API = 'http://10.99.2.20:5000/query/pages';
	protected ?array $categories = null;


	public function getPages($title = null, $offset = null, $sort = null, $count = null)
	{
		$data = json_encode(
			array(
				'title' => $title ?? '',
				'count' => $count ?? self::DEFAULT_COUNT,
				'offset' => $offset ?? self::DEFAULT_OFFSET,
				'sort' => $sort ?? 'last',
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
		$pages = json_decode(file_get_contents(self::API, false, $context), true);
		$result = array(
			'content' => $pages,
			'type' => 'page',
		);

		return $result;
	}
}
