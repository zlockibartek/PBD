<?php
require_once('C:\xampp\htdocs\mongo\DBManager\DBManager.php');

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Home\DBManager\DBManager;

$DBManager = new DBManager();

return ConsoleRunner::createHelperSet($DBManager->entityManager);