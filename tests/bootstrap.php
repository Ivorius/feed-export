<?php
require __DIR__ . '/../../../autoload.php';


Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');
// create temporary directory
define('TEMP_DIR', __DIR__ . '/temp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
Tester\Helpers::purge(TEMP_DIR);