<?php

namespace Marcio1002\DiscordWebhook;

use
    Marcio1002\DiscordWebhook\Contracts\DiscordWebhookContract,
    Marcio1002\DiscordWebhook\Helpers\Validator;

use
    React\Http\Browser,
    React\Promise\Promise;

class DiscordWebhook implements DiscordWebhookContract
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
        $this->options = $this->sanitizeOptions($options);
    }


    /**
     * Send a message to the webhook
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[]|\Marcio1002\DiscordWebhook\Message $message
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
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[]|\Marcio1002\DiscordWebhook\Message $message
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
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[]|\Marcio1002\DiscordWebhook\Message $message
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

        if($message instanceof Message) {
            $webhook_props = $message->toArray();
        }

        if ($message instanceof MessageEmbed) {
            $webhook_props['embeds'] = [$message->getEmbed()];
        }

        if (is_array($message)) {
            $is_message_embed = Validator::arrayEvery(fn($v) => $v instanceof MessageEmbed, $message);
            $is_array_php = Validator::arrayEvery(fn($v) => !($v instanceof Message), $message); 

            if(!$is_message_embed && !$is_array_php) {
                throw new \InvalidArgumentException('Expected an array php or array of MessageEmbed object');
            }

            if($is_message_embed) {
                $webhook_props['embeds'] = array_map(fn(MessageEmbed $v) => $v->getEmbed(), $message);
            }

            if(!$is_message_embed) {
                $webhook_props = $this->sanitizeProps($message);
            }
        }

        if (!empty($options)) {
            $webhook_props = array_merge($webhook_props, $options);
        }


        return json_encode($webhook_props);
    }


    /**
     * Sanitize the options
     *
     * @param array $options
     * @return void
     */
    private function sanitizeOptions(array $options)
    {
        $options_in_includes = ['webhook_url', 'tts', 'thread_name'];

        $options = array_filter(
            $options,
            fn($opKey) =>  in_array($opKey, $options_in_includes),
            ARRAY_FILTER_USE_KEY
        );

        return $options;
    }

    /**
     * Sanitize the props webhook
     *
     * @param array $props
     * @return void
     */
    private function sanitizeProps(array $props)
    {
        $props_in_includes = ['content', 'username', 'avatar_url', 'tts', 'embeds'];

        $props = array_filter(
            $props,
            fn($msgKey) =>  in_array($msgKey, $props_in_includes),
            ARRAY_FILTER_USE_KEY
        );

        return $props;
    }
}