<?php

use Bot\Helper;

define("ROOT_DIR", dirname(__DIR__));

require_once ROOT_DIR . "/vendor/autoload.php";

Helper::loadDotEnv(ROOT_DIR);
