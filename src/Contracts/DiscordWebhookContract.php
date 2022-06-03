<?php

namespace Marcio1002\DiscordWebhook\Contracts;

use React\Promise\Promise;

interface DiscordWebhookContract {

    /**
     * Send a message to the webhook
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[]|\Marcio1002\DiscordWebhook\Message $message
     * @return Promise
     */
    public function sendMessage($message): Promise;

    /**
     * Send a message to the webhook synchronously
     *
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[]|\Marcio1002\DiscordWebhook\Message $message
     * @return mixed
     */
    public function sendMessageSync($message);
}