<?php

require_once __DIR__ . "/environment.php";

use TelegramSDK\BotAPI\Exception\TelegramException;

define("RED_COLOR", "\033[0;31m");
define("GREEN_COLOR", "\033[0;32m");
define("DEFAULT_COLOR", "\033[0m\n");

try {
    $res = (\Bot\Bot::make($_ENV['BOT_TOKEN'], false))->bootstrap($_ENV['WEBHOOK_ENDPOINT'], $_ENV['TELEGRAM_SECRET'], true);
    echo GREEN_COLOR . "Webhook set correctly.\n" . DEFAULT_COLOR;
} catch(TelegramException $e) {
    echo RED_COLOR . "There was an error setting up the webhook: " . $e->getResponseBody()->description . "\n" . DEFAULT_COLOR;
    exit(1);
}
