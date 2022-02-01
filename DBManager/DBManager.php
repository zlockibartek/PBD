<?php

namespace Home\DBManager;

use DateTime;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager as EM;
use Home\DBManager\Tables\Comment;

require_once "C:\\xampp\htdocs\mongo\\vendor\autoload.php";
class DBManager
{
	const PATH = 'C:/xampp/htdocs/mongo/DBManager/Tables/';
	const DEFAULT_COUNT = 21;
	const DEFAULT_OFFSET = 0;

	public $entityManager;
	public function __construct()
	{
		$entities = scandir(self::PATH);
		foreach ($entities as $entity) {
			if (strpos($entity, '.php')) {
				require_once(self::PATH . '\\' . $entity);
			}
		}
		$isDevMode = true;
		$proxyDir = null;
		$cache = null;
		$useSimpleAnnotationReader = false;
		$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . '\Tables'), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
		$conn = array(
			'dbname' => 'mongo',
			'user' => 'root',
			'password' => '',
			'host' => 'localhost',
			'driver' => 'pdo_mysql'
		);
		$this->entityManager = EM::create($conn, $config);
		// return DependencyFactory::fromEntityManager($migrationConfig, new ExistingEntityManager($this->entityManager));
	}

	public function getPages($sort = 'popular', $title = null, $offset = 0, $count = 21)
	{
		$pageRepository = $this->entityManager->getRepository('Home\DBManager\Tables\Page');
		
		if ($title) {
			return $pageRepository->findBy(['title' => $title], ['createTime' => 'ASC'], $count, $offset);	
		}
		else {
			return $pageRepository->findAll();
		}
	}
	public function getComments($pageId = null)
	{
		if (!$pageId)
			return [];
		return $this->entityManager->getRepository('Home\DBManager\Tables\Comment')->findBy(['pageId' => $pageId]);

	}

	public function getLogs() {
		return $this->entityManager->getRepository('Home\DBManager\Tables\Comment')->findAll();
	}
	
	// public function setComments($content, $main = null, $moderator = null)
	// {
	// 	$html = '<div id="commentsBox" >
    //         <div id="makeCommentBox">
	// 		<form method="POST" enctype="multipart/form-data">
    //             <p class="LebelBasic"></p>
    //             <input type="text" id="makeCommentText" name="send" required minlength="4" size="300"> <!--Pozmieniać wartości-->
    //             <div id="makeCommentFiles">
    //                 <input type="file" id="commentFile1" name="file" accept=".jpg,.jpeg,.png,.gif">
    //             </div>
    //             <input type="submit" id="makeCommentSubmit" Value="Comment">
	// 			</form>
    //         </div>
    //         <div id="commentsListBox">
    //             <ul id="commentList">';
	// 	if ($content) {
	// 		foreach ($content as $comment) {
	// 			$file = isset($comment['attachements']) && $comment['attachements'] ? self::SERVER . $comment['attachements'][0] : '';
	// 			$html .= '<li>
	// 							<div class="commentBox">
	// 								<div class="commentCred">
	// 									<p class="commentUsername">' . $comment['eamil'] . '</p>
	// 									<p class="commentDate">' . date('Y-m-d H:i:s', $comment['timestamp']/1000) . '</p>
	// 								</div><div>
	// 								<p class="commentText">' . $comment['text'] . '</p>';
	// 								$html .= $this->displayAttachment($file);
	// 								$html .= '</div><div>';
	// 								$html .= $moderator ? '<a href="?page=' . $_GET['page'] . '&remove=' . $comment['timestamp'] . '"><button>Delete</button></a>' : '';	
	// 						$html .= '</div></li>';
	// 		}
	// 	}
	// 	$html .= '</ul>
    //         </div>
    //     </div>';

	// 	$this->view .= $html;
	// }

	public function getPage($pageId = null)
	{
		if (!$pageId)
			return null;
		return $this->entityManager->getRepository('Home\DBManager\Tables\Page')->findBy(['id' => $pageId]);
	}

	public function sendComment($text, $nickname, $email, $pageId, $attachment) {
		echo '<pre>';
		var_dump($attachment);
		echo '</pre>';
		$comment = new Comment();
		$comment->setContent($text);
		$comment->setNickname($nickname);
		$comment->setUserEmail($email);
		$comment->setPageId($pageId);
		$comment->setAttachments($attachment);
		$comment->setCreateTime(new DateTime(date('Y:m:d H:i:s')));
		$this->entityManager->persist($comment);
		$this->entityManager->flush();
	}
	
	public function getUser() {

	}
}
