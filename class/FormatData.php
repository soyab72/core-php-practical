<?php

// Code format is not okay

class FormatData
{
    /**
     * Data format for all news.
     *
     * @param array $row
     * @return array
     */
	protected function formatNewsData($rows) : array
	{
		try {
			$news = [];  // what if $row is empty or other type data in that check $row
			foreach($rows as $row) {
				$n = new News();  // $n should be proper var name
				$comments = isset($row['comments']) ? CommentManager::getInstance()->formatNewsComments($row['comments']) : [];
				$news[] = $n->setId($row['id'])
				  ->setTitle($row['title'])
				  ->setBody($row['body'])
				  ->setComments($comments)
				  ->setCreatedAt($row['created_at']);
			}
			return $news;
		} catch (\Exception $e) {
			// Return line number and file number as well applicable for all place
			return $e->getMessage();
		}
	}
    
    /**
     * Data format for all comment.
     *
     * @param array $row
     * @return array
     */
	protected function formatCommentsData($rows) : array
	{
		try {
			$comments = []; // check $row data in if condition
			foreach($rows as $row) {
				$n = new Comment();
				$comments[] = $n->setId($row['id'])
				->setBody($row['body'])
				->setCreatedAt($row['created_at'])
				->setNewsId($row['news_id']);
			}

			return $comments;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
     * Data structure change for news comments.
     *
     * @param array $row
     * @return ?array.    It should be mixed data type in this type of case but instead return null return blank array []
     */
	protected function formatNewsComments($comments) : ?array
	{
		try {
			if ($comments) {
				$comments = array_map(function ($input) {
					return explode(':',$input);
				}, explode(';', $comments));
				return $comments;
			} else {
				return null;
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}
}

?>
