<?php

session_start();

require(__DIR__ . '/../core/app.php');
require(__DIR__ . '/../core/routes.php');

$app->run();