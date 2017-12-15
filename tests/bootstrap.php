<?php
require __DIR__ . '/../../../../../vendor/autoload.php';

//require_once __DIR__ . '/../src/IGenerator.php';
//require_once __DIR__ . '/../src/IWriter.php';
//require_once __DIR__ . '/../src/IItem.php';
//require_once __DIR__ . '/../src/Item.php';
//require_once __DIR__ . '/../src/Heureka/AvailGenerator.php';
//require_once __DIR__ . '/../src/Heureka/AvailItem.php';
//require_once __DIR__ . '/../src/XmlWriter.php';
//require_once __DIR__ . '/../src/XmlElement.php';


Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');
// create temporary directory
define('TEMP_DIR', __DIR__ . '/temp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
Tester\Helpers::purge(TEMP_DIR);

$loader = new Nette\Loaders\RobotLoader;
$loader->addDirectory(__DIR__ . '/../src');
$loader->setTempDirectory( __DIR__ . '/temp/');
$loader->register();