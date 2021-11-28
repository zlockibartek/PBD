<?php

namespace Home\Collections;

class Comments
{
	const API = 'http://10.99.2.20:5000/query/comments';
	protected $comments; 

	public function getComments($pageId = null)
	{
		$data = json_encode(
			array(
				'pageid' => $pageId,
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
		if ($pageId) {
			return $this->renderAcceptedComments($result);
		}
		return $this->renderAwaitingComments();
		$result = isset($result['parse']) ? $result['parse'] : null;
		if (!$result)
			return $result;
		$content['text'] = isset($result['text']) ? $result['text'] : '';
		$content['title'] = isset($result['title']) ? $result['title'] : '';
		$content['pageId'] = isset($result['pageid']) ? $result['pageid'] : '';
		return $content;
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

	public function renderAcceptedComments() {

	}

	public function renderAwaitingComments() {

	}
}
