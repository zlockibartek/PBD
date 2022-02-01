<?php

namespace Home\DBManager;

use DateTime;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager as EM;
use Home\DBManager\Tables\Category;
use Home\DBManager\Tables\Comment;
use Home\DBManager\Tables\Contains;
use Home\DBManager\Tables\Log;
use Home\DBManager\Tables\Page;
use Home\DBManager\Tables\User;

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
		$result = $this->entityManager->createQueryBuilder();
		$result->select('count(pageId)');
		// , COUNT(*) as NUM
		// echo '<pre>';

		// var_dump($result->getQuery()->getSingleScalarResult());
		// echo '</pre>';
		if ($title) {
			return $pageRepository->findBy(['title' => $title]);
		}

		if ($sort == 'last') {
			return $pageRepository->findBy([], ['createDate']);
		}
		return $pageRepository->findAll();
	}

	public function getComments($pageId = null)
	{
		if (!$pageId)
			return [];
		return $this->entityManager->getRepository('Home\DBManager\Tables\Comment')->findBy(['pageId' => $pageId], ['createTime' => 'DESC']);
	}

	public function getLogs()
	{
		return $this->entityManager->getRepository('Home\DBManager\Tables\Log')->findBy([], [], 10);
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
	public function login($email, $password)
	{
		$user = $this->entityManager->getRepository('Home\DBManager\Tables\User')->findBy(['email' => $email]);
		if (empty($user)) {
			return;
		}
		if (password_verify($password, $user[0]->getPassword())) {
			setcookie('header_cookies', '', time() + 3600, '/');
			setcookie('roles', $user[0]->getRole(), time() + 3600, '/');
			setcookie('username', $user[0]->getNickname(), time() + 3600, '/');
			$this->addLog('login', 'User logged: ' . $email);
		}
	}

	public function register($nickname, $email, $password, $name)
	{
		$user = new User();
		$repository = $this->entityManager->getRepository('Home\DBManager\Tables\User');
		if (!empty($repository->findBy(['email' => $email])))
			return;
		if (!empty($repository->findBy(['nickname' => $nickname]))) {
			return;
		}
		$user->setName($name);
		$user->setNickname($nickname);
		$user->setEmail($email);
		$user->setRole('user');
		$this->addLog('register', 'New user: ' . $email);
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$user->setPassword($hashedPassword);
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	public function removeComment($commentId)
	{
		$comment = $this->entityManager->getRepository('Home\DBManager\Tables\Comment')->findBy(['id' => $commentId]);
		if (!empty($comment)) {
			$this->entityManager->remove($comment[0]);
			$this->entityManager->flush();
		}
	}

	public function sendPage($url)
	{
		$context = stream_context_create();
		$content = json_decode(file_get_contents($url, FALSE, $context));

		if (!$content) {
			return;
		}
		$content = $content->parse;
		$context = stream_context_create();
		$categoryContent = json_decode(file_get_contents('https://en.wikipedia.org/w/api.php?action=query&prop=categories&format=json&pageids=' . $content->pageid, FALSE, $context));
		$pageId = $content->pageid;
		$categories = $categoryContent->query->pages->$pageId->categories;

		$page = new Page();
		$repository = $this->entityManager->getRepository('Home\DBManager\Tables\Page')->findBy(['title' => $content->title]);
		if (!empty($repository)) {
			return;
		}
		$page->setTitle($content->title);
		$page->setId($content->pageid);
		$page->setCreateTime(new DateTime(date('Y:m:d H:i:s')));
		$this->entityManager->persist($page);
		$this->entityManager->flush();

		$repository = $this->entityManager->getRepository('Home\DBManager\Tables\Category');
		foreach ($categories as $category) {
			$results = $repository->findBy(['title' => $category->title]);
			if (!empty($results)) {
				$categoryEntity = $results[0];
			} else {
				$categoryEntity = new Category();
				$categoryEntity->setTitle($category->title);
				$this->entityManager->persist($categoryEntity);
				$this->entityManager->flush();
			}
			$contains = new Contains();
			$contains->setCategoryId($categoryEntity->getId());
			$contains->setPageId($page->getId());
			$this->entityManager->persist($contains);
			$this->entityManager->flush();
		}
	}

	public function getPage($pageId = null)
	{
		if (!$pageId)
			return null;
		$page =	$this->entityManager->getRepository('Home\DBManager\Tables\Page')->findBy(['id' => $pageId]);
		$context = stream_context_create();
		$title = str_replace(' ', '_', $page[0]->getTitle());
		$url = 'https://en.wikipedia.org/w/api.php?action=parse&format=json&page=';
		$url .= $title . '&prop=text&formatversion=2';
		$content = json_decode(file_get_contents($url, FALSE, $context));
		$content  = $content->parse;
		return ['text' => $content->text, 'title' => $page[0]->getTitle()];
	}

	public function sendComment($text, $nickname, $email, $pageId, $attachment)
	{
		if (!$nickname)
			return;
		$comment = new Comment();
		$comment->setContent($text);
		$comment->setNickname($nickname);
		$comment->setUserEmail($email);
		$comment->setPageId($pageId);
		$comment->setAttachments($attachment);
		$comment->setCreateTime(new DateTime(date('Y:m:d H:i:s')));
		$this->addLog('new comment', 'New message: ' . $text);
		$this->entityManager->persist($comment);
		$this->entityManager->flush();
	}

	public function addLog($action, $message)
	{
		$log = new Log();
		$log->setAction($action);
		$log->setMessage($message);
		$log->setCreateTime(new DateTime(date('Y:m:d H:i:s')));
		$this->entityManager->persist($log);
		$this->entityManager->flush();
	}
}
