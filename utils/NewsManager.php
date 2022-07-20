<?php

require_once(ROOT . '/class/FormatData.php');

class NewsManager extends FormatData
{
	private static $instance = null;
	private $query;
	
	private function __construct()
	{
		require_once(ROOT . '/utils/CommentManager.php');
		require_once(ROOT . '/class/News.php');
		require_once(ROOT . '/utils/Query.php');
		$this->query = Query::getInstance();
	}

	/**
     * Create or retrieve the instance of class.
     * 
     * @return object
     */
	public static function getInstance() : object
	{
		if (null === self::$instance) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	/**
     * Fetch all news with their comments.
     *
     *
     * @return array
     */
	public function listNewsWithComment() : array
	{
		try {
			$rows = $this->query->select('n.id,n.title,n.body,n.created_at,GROUP_CONCAT(CONCAT_WS(":", c.id, c.body) SEPARATOR ";") as comments')->from('news n')->leftJoin('comment c ON c.news_id = n.id')->groupBy('n.id')->get();
			return [
				"error" => false,
				"data" => $this->formatNewsData($rows),
			];

		} catch (\Exception $e) {
			return [
				"error" => true,
				"message" => $e->getMessage(),
			];
		}
	}

	/**
     * Fetch all news.
     *
     *
     * @return array
     */
	public function listNews() : array
	{
		try {
			$rows = $this->query->select('*')->from('news')->get(); // avoid * 
			return [
				"error" => false,
				"data" => $this->formatNewsData($rows),
			];

		} catch (\Exception $e) {
			return [
				"error" => true,
				"message" => $e->getMessage(),
			];
		}
	}

	/**
	* Insert new news record to database
	*
	* @param string $title
	* @param string $body
	*
	* @return array
	*/
	public function addNews($title, $body) : array
	{
		try {
			if ($title && $body) {
				$param = [
					"title" => $title,
					"body" => $body,
					"created_at" => date('Y-m-d'),
				];
				$id = $this->query->insert('news',$param);
				return [
					"error" => false,
					"data" => $id,
				];
			}
			return [
					"error" => false,
					"message" => MESSAGE['AddNews'],
				];
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
	public function deleteNews($id) : array
	{
		try {
			if ($id) {
				$this->query->delete('news',$id);
				return [
					"error" => false,
					"message" => MESSAGE['DeleteSucces'],
				];
			} 
			/// removed else
			return [
					"error" => true,
					"data" => MESSAGE['NewsDelete']
				];
		} catch (\Exception $e) {
			return [
				"error" => true,
				"data" => $e->getMessage(),
			];
		}
	}
}
