<?php

namespace Home\Collections;

class Comments
{
	const DEFAULT_COUNT = 20;
	const DEFAULT_OFFSET = 0; 
	const API = 'http://10.99.2.20:5000/query/comments';
	protected $comments; 

	public function getComments($pageId = null)
	{
		$data = json_encode(
			array(
				'pageid' => intval($pageId),
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
		$result = json_decode(file_get_contents(self::API, false, $context), true);

		return $result;
	}

	public function addComment($pageId, $userId, $text)
	{
		$data = json_encode(
			array(
				'pageid' => $pageId,
				'userid' => $userId,
				'text' => $text,
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
		$result = json_decode(file_get_contents(self::API, false, $context), true);
		return $result;
	}

	public function renderAcceptedComments() 
	{

	}

	public function renderAwaitingComments() 
	{
		return '';//'<div><form method="POST" enctype="multipart/form-data"><input type="file" name="comment" id="commentFile"><input type="submit" value="save"></form></div>';
	}
}
