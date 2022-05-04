<?php

namespace Marcio1002\DiscordWebhook;

use
    React\Http\Browser,
    React\Promise\Promise;

class DiscordWebhook
{

    private $browser;
    private array $options;

    /**
     *
     * @param string $url
     */
    public function __construct(array $options)
    {

        $this->browser = new Browser();
        $this->options = $options;
    }


    /**
     * Send a message to the webhook
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[] $message
     * @return Promise
     */
    public function sendMessage($message): Promise
    {

        return $this->browser->post(
            $this->options['webhook_url'],
            [
                'Content-Type' => 'application/json',
            ],
            $this->resolveMessage($message)
        );
    }


    /**
     * Send a message to the webhook synchronously
     *
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[] $message
     * @return mixed
     */
    public function sendMessageSync($message)
    {

        return \Clue\React\Block\await($this->browser->post(
            $this->options['webhook_url'],
            [
                'Content-Type' => 'application/json',
            ],
            $this->resolveMessage($message)
        ));
    }

    /**
     * 
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[] $message
     * @return string
     */
    private function resolveMessage($message)
    {
        $webhook_props = [];
        $options = $this->options;
        unset($options['webhook_url']);

        if (is_string($message)) {
            $webhook_props['content'] = $message;
        }

        if ($message instanceof MessageEmbed) {
            $webhook_props = $message->toArray();
            $webhook_props['embeds'] = [$webhook_props['embed']];
            unset($webhook_props['embed']);
        }

        if (is_array($message)) {
            $append_props = function ($p) {
                if ($p instanceof MessageEmbed) {
                    return $p->getEmbed();
                }

                return $p;
            };

            $webhook_props['embeds'] = array_map(
                $append_props,
                $message,
            );
        }

        if (!empty($options)) {
            $webhook_props = array_merge($webhook_props, $options);
        }


        return json_encode($webhook_props);
    }
}