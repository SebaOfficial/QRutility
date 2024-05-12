<?php

use Bot\{Bot, QR, Helper};

it("sends a message and deletes it", function () {
    $bot = new Bot($_ENV['BOT_TOKEN'], false);

    $res = $bot->sendMessage(
        'Hello World',
        [[['text' => 'An Url', 'url' => 'https://example.com'], ['text' => 'A callback', 'callback_data' => 'Hello World']]],
        $_ENV['OWNER_ID'],
        false
    );

    $this->assertTrue($res->body->ok);

    $res = $bot->deleteMessage([
        'chat_id' => $_ENV['OWNER_ID'],
        'message_id' => $res->body->result->message_id,
    ]);

    $this->assertTrue($res->body->ok);
});

it("sends a QR code as a ducument and deletes it", function () {
    $bot = new Bot($_ENV['BOT_TOKEN'], false);
    $qr = new QR(Helper::getRandomFilePath('qr'));

    $res = $bot->sendDocument($qr->generate('hello world'), 'qr.png', '<b>This is a caption</b>', $_ENV['OWNER_ID']);
    $this->assertTrue($res->body->ok);

    $res = $bot->deleteMessage([
        'chat_id' => $_ENV['OWNER_ID'],
        'message_id' => $res->body->result->message_id,
    ]);

    $this->assertTrue($res->body->ok);
    $this->assertTrue($qr->unlink());
});
