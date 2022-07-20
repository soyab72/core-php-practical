<?php

define('ROOT', __DIR__);
require_once(ROOT . '/utils/NewsManager.php');
require_once(ROOT . '/utils/CommentManager.php');
define('MESSAGE',include('message.php'));

$newsData = NewsManager::getInstance()->listNewsWithComment();
foreach ($newsData['data'] as $news) {
	echo "<pre>";
	echo("############ NEWS " . $news->getTitle() . " ############\n");
	echo($news->getBody() . "\n");
	if (!empty($news->getComments())) {
		foreach ($news->getComments() as $comment) {
			echo("Comment " . $comment[0] . " : " . $comment[1] . "\n");
		}
	}
}

// $commentManager = CommentManager::getInstance();
// $c = $commentManager->deleteComment(16);
// print_r($c);