<?php
require_once('C:\xampp\htdocs\mongo\DBManager\DBManager.php');

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Home\DBManager\DBManager;

$DBManager = new DBManager();
$migrationConfig = new PhpFile("migrations.php");
return DependencyFactory::fromEntityManager($migrationConfig, new ExistingEntityManager($DBManager->entityManager));	
return ConsoleRunner::createHelperSet($DBManager->entityManager);