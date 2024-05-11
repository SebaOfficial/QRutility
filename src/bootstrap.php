<?php

require_once __DIR__ . "/environment.php";

(\Bot\Bot::make($_ENV['BOT_TOKEN'], false))->bootstrap($_ENV['WEBHOOK_ENDPOINT'], $_ENV['TELEGRAM_SECRET'], true);
