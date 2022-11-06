<?php

namespace Marcio1002\DiscordWebhook;

use Marcio1002\DiscordWebhook\Contracts\DiscordWebhookContract;
use Marcio1002\DiscordWebhook\Helpers\HttpHelper;
use React\Http\Browser;

class DiscordWebhook implements DiscordWebhookContract
{

    private string $base_url = 'https://discord.com/api/webhooks/';
    private string $base_path = '';
    private Browser $browser;
    private array $options;


    public function __construct(array $options)
    {
        $this->options = HttpHelper::sanitizeOptions($options);

        if (!array_key_exists('webhook_id', $this->options) || !array_key_exists('token', $this->options)) {
            throw new \Exception('Webhook id and token is required');
        }

        ['webhook_id' => $webhook_id, 'token' => $token] = $this->options;

        $this->browser = (new Browser())->withBase($this->base_url);

        $this->base_path = "{$webhook_id}/{$token}";

        unset($this->options['webhook_id'], $this->options['token']);
    }


    public function sendMessage($message, bool $sync = false)
    {

        $response = $this->browser->post(
            "{$this->base_path}?wait=true",
            ['Content-Type' => 'application/json'],
            $this->resolveMessage($message)
        );

        return HttpHelper::resolveResponse($response, $sync);
    }

    public function editMessage(string $message_id, $message, bool $sync = false)
    {
        $response = $this->browser->patch(
            "{$this->base_path}/messages/$message_id",
            ['Content-Type' => 'application/json'],
            $this->resolveMessage($message)
        );

        return HttpHelper::resolveResponse($response, $sync);
    }


    public function getMessage(string $message_id, bool $sync = false)
    {
        $response = $this->browser->get(
            "{$this->base_path}/messages/$message_id",
            ['Content-Type' => 'application/json']
        );

        return HttpHelper::resolveResponse($response, $sync);
    }


    public function deleteMessage(string $message_id, $sync = false)
    {
        $response = $this->browser->delete(
            "{$this->base_path}/messages/$message_id"
        );

        return HttpHelper::resolveResponse($response, $sync);
    }

    /**
     * 
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[]|\Marcio1002\DiscordWebhook\Message $message
     * @return string
     */
    private function resolveMessage($message)
    {
        $body = array_merge(HttpHelper::resolveMessage($message), $this->options);

        return json_encode($body);
    }
}