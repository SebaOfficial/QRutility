<?php

use Bot\Helper;

require_once dirname(__DIR__) . "/environment.php";

$bot = Bot\Bot::make($_ENV['BOT_TOKEN']);
$update = $bot->updates();

if(!$update->isFromTelegram($_ENV['TELEGRAM_SECRET'])) {
    $bot->asPayload(false);
    header("Location: https://t.me/" . $bot->getMe()->body->result->username, true);
    exit;
}

$i18n = new Bot\i18n(ROOT_DIR . "/locale/", $bot->getUserLanguage(), 'en', [
    '{{mention}}' => "<a href='" . $update->getUser()->id . "'>" . $update->getUser()->first_name . "</a>",
]);

$locale = $i18n->load();


if($msg = $locale->{$update->getTrigger()} ?? null) { // A command
    $bot->sendMessage($msg->text, $msg->keyboard ?? null);
} elseif($message = $update->getMessage()) {
    if(isset($message->document) || isset($message->photo)) { // A qr code to be decoded
        $url = $bot->getFileUrl(($message->document ?? end($message->photo))->file_id);
        $bot->sendMessage(strtr($locale->qr_result->extracted, ['{{res}}' => (new Bot\QR($url))->read('resource')]));
    } elseif($txt = $message->text ?? $message->caption ?? null) { // A text to be encoded into a QR
        $qr = new Bot\QR(Bot\Helper::getRandomFilePath('qr'));
        $isPrivateChat = $update->getChat()->type == 'private';

        if(!$isPrivateChat) { // In public chats the bot expects "/qr ..."
            $txt = preg_match('/[\/\.]qr\s+(\S+)/', $txt, $matches) ? $matches[1] : null;
        }

        if(isset($txt)) {
            $bot->sendDocument(
                $qr->generate($txt),
                'qr.png',
                strtr($locale->qr_result->generated, ["{{txt}}" => $txt]),
                replyParameters: !$isPrivateChat ? [
                    'message_id' => $update->getMessage()->message_id
                ] : null
            );

            $qr->unlink();

            if($isPrivateChat) {
                $bot->deleteMessage([
                    'chat_id' => $update->getChat()->id,
                    'message_id' => $update->getMessage()->message_id,
                ]);
            }
        }
    }
}
