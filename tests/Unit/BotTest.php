<?php

use Bot\Bot;

it("creates an instance of a bot", function () {
    $this->assertTrue(isset($_ENV['BOT_TOKEN']));
    $bot = Bot::make($_ENV['BOT_TOKEN'], false);

    $this->assertTrue($bot->isValidToken(true));
});

it("gets information about the bot", function () {
    $bot = Bot::make($_ENV['BOT_TOKEN'], false);

    $this->assertTrue($bot->getMe()->body->ok);
});
