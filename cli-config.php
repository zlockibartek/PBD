<?php
require_once('C:\xampp\htdocs\mongo\DBManager\DBManager.php');

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Home\DBManager\DBManager;

$DBManager = new DBManager();
$migrationConfig = fopen("migrations.php", "w");
// return DependencyFactory::fromEntityManager($migrationConfig, new ExistingEntityManager($DBManager->entityManager));	
return ConsoleRunner::createHelperSet($DBManager->entityManager);