<?php

require_once(ROOT . '/class/FormatData.php');

class CommentManager extends FormatData
{
	private static $instance = null;
	private $query;

	private function __construct()
	{
		require_once(ROOT . '/database/DBBuilder.php');
		require_once(ROOT . '/class/Comment.php');
		require_once(ROOT . '/utils/Query.php');
		$this->query = Query::getInstance();
	}

	/**
     * Create or retrieve the instance of class.
     * 
     * @return object
     */
	public static function getInstance()
	{
		if (null === self::$instance) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	/**
     * Fetch all comments.
     *
     *
     * @return array
     */
	public function listComments() : array
	{
		try {
			$rows = $this->query->select('*')->from('comment')->get();
			$comments = $this->formatCommentsData($rows);
			return [
				"error" => false,
				"data" => $comments,
			];
		} catch (\Exception $e) {
			return [
				"error" => true,
				"message" => $e->getMessage()
			];
		}
	}

	/**
	* Insert new comment record to database
	*
	* @param string $body
	* @param int $newsId
	*
	* @return array
	*/
	public function addCommentForNews($body, $newsId) : array
	{
		try {
			if ($newsId && $body) {
				$check = $this->query->select('id')->from("news")->where('id='.$newsId)->fetch();
				if (!$check) {
					return [
						"error" => true,
						"message" => MESSAGE['NewsNotFound'],
					];
				}
				$param = [
					"body" => $body,
					"created_at" => date('Y-m-d'),
					"news_id" => $newsId,
				];
				$id = $this->query->insert('comment',$param);
				return [
					"error" => false,
					"data" => $id,
				];
			} else {
				return [
					"error" => false,
					"message" => MESSAGE['AddComment'],
				];
			}
		} catch (\Exception $e) {
			return [
				"error" => false,
				"message" => $e->getMessage(),
			];
		}
	}

	/**
	* Delete news and its related comments
	*
	* @param int $id
	*
	* @return array
	*/
	public function deleteComment($id) : array
	{
		try {
			if ($id) {
				$this->query->delete('comment',$id);
				return [
					"error" => false,
					"message" => MESSAGE['DeleteSucces'],
				];
			} else {
				return [
					"error" => true,
					"data" => MESSAGE['CommentDelete']
				];
			}
		} catch (\Exception $e) {
			return [
				"error" => true,
				"data" => $e->getMessage(),
			];
		}
	}
}