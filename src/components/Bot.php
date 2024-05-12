<?php

namespace Bot;

use TelegramSDK\BotAPI\Telegram\{Update, TelegramResponse};

class Bot extends \TelegramSDK\BotAPI\Telegram\Bot
{
    private static array $instances = [];
    private ?Update $update;

    public function __construct(string $token, bool $replyWithPayload)
    {
        parent::__construct($token, Update::UPDATES_FROM_WEBHOOK, replyWithPayload: $replyWithPayload);
    }

    public static function make(string $token, bool $replyWithPayload = true): static
    {
        if(!isset(self::$instances[$token][$replyWithPayload])) {
            self::$instances[$token][$replyWithPayload] = new static($token, $replyWithPayload);
        }

        return self::$instances[$token][$replyWithPayload];
    }

    public function bootstrap(string $webhookEnpoint, string $telegramSecret, bool $dropPendingUpdates): TelegramResponse
    {
        return parent::setWebhook([
            "url" => $webhookEnpoint,
            "drop_pending_updates" => $dropPendingUpdates,
            "allowed_updates" => [
                "message",
                "inline_query"
            ],
            "secret_token" => $telegramSecret,
        ]);
    }

    public function updates(?int $offset = null): ?Update
    {
        if(!isset($this->update)) {
            $this->update = parent::updates($offset);
        }

        return $this->update;
    }

    public function getUserLanguage(string $fallback = "en"): string
    {
        return $this->updates()->getUser()->language_code ?? $fallback;
    }

    public function sendMessage(string $text, ?array $keyboard = null, int|string|null $chatID = null, bool $asPayload = true): ?TelegramResponse
    {
        $chatID = $chatID ?? $this->update->getChat()->id;

        return parent::{$asPayload ? 'replyAsPayload' : 'sendRequest'}('sendMessage', [
            'chat_id' => $chatID,
            'text' => $text,
            'parse_mode' => "HTML",
            'reply_markup' => isset($keyboard) ? ['inline_keyboard' => $keyboard] : null,
        ]);
    }

    public function sendDocument(string $path, string $fileName, ?string $caption = null, int|string|null $chatID = null): TelegramResponse
    {
        $chatID = $chatID ?? $this->update->getChat()->id;

        return parent::sendRequest("sendDocument", [
            [
                'name' => 'chat_id',
                'contents' => $chatID,
            ],
            [
                'name' => 'document',
                'contents' => file_get_contents($path),
                'filename' => $fileName,
            ],
            [
                'name' => 'parse_mode',
                'contents' => 'HTML',
            ],
            isset($caption) ? [
                'name' => 'caption',
                'contents' => $caption,
            ] : null,
        ], multipart: true);
    }


    public function getFileUrl(string $id): string
    {
        return parent::getApiUrl() . 'file/bot' . $this->token . "/" . parent::sendRequest('getFile', [
            'file_id' => $id,
        ])->body->result->file_path;
    }
}
