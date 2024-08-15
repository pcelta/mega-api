<?php

use Lib\Application;

require_once __DIR__ .'/../bootstrap.php';

$application = new Application();
$application->run();

phpinfo(); die();
