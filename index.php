<?php

session_start();
date_default_timezone_set('Europe/Kiev');

require(__DIR__ . '/core/app.php');
require(__DIR__ . '/core/routes.php');

$app->run();