<?php

namespace Marcio1002\DiscordWebhook\Contracts;

use Marcio1002\DiscordWebhook\Message;
use Marcio1002\DiscordWebhook\MessageEmbed;

use Psr\Http\Message\ResponseInterface;


interface DiscordWebhookContract
{

    /**
     * Send a message to the webhook
     * @param string|array|MessageEmbed|MessageEmbed[]|Message $message
     * @param bool $sync
     * @return PromiseContract<ResponseInterface>|ResponseInterface
     */
    public function sendMessage($message, bool $sync = false);

    /**
     * Edit a message in the webhook
     *
     * @param string $message_id
     * @param string|array|MessageEmbed|MessageEmbed[]|Message $message
     * @param bool $sync
     * @return PromiseContract<ResponseInterface>|ResponseInterface
     */
    public function editMessage(string $message_id, $message, bool $sync = false);

    /**
     * Get a message from the webhook
     *
     * @param string $message_id
     * @param bool $sync
     * @return mixed|PromiseContract<ResponseInterface>|ResponseInterface
     */
    public function getMessage(string $message_id, bool $sync = false);

    /**
     * Delete a message from the webhook
     *
     * @param string $message_id
     * @param bool $sync
     * @return mixed|PromiseContract<ResponseInterface>|ResponseInterface
     */
    public function deleteMessage(string $message_id, bool $sync = false);
}