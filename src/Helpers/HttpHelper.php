<?php

namespace Marcio1002\DiscordWebhook\Helpers;

use Marcio1002\DiscordWebhook\Contracts\PromiseContract;
use Marcio1002\DiscordWebhook\Message;
use Marcio1002\DiscordWebhook\MessageEmbed;
use Marcio1002\DiscordWebhook\Helpers\Traits\Format;


use function React\Async\await;
use Psr\Http\Message\ResponseInterface;

class HttpHelper
{
    /**
     * Resolve response
     *
     * @param PromiseContract<ResponseInterface> $response
     * @param bool $sync
     * @return PromiseContract<array>|array
     */
    public static function resolveResponse($response, bool $sync)
    {
        if ($sync) {
            return static::toArray(await($response));
        }

        return $response->then(fn ($res) => static::toArray($res));
    }

    /**
     * 
     * @param string|array|\Marcio1002\DiscordWebhook\MessageEmbed|\Marcio1002\DiscordWebhook\MessageEmbed[]|\Marcio1002\DiscordWebhook\Message $message
     * @return array
     */
    public static function resolveMessage($message)
    {
        $webhook_props = [];

        if (is_string($message)) {
            $webhook_props['content'] = $message;
        }

        if ($message instanceof Message) {
            $webhook_props = $message->toArray();
        }

        if ($message instanceof MessageEmbed) {
            $webhook_props['embeds'] = [$message->getEmbed()];
        }

        if (is_array($message)) {
            $is_message_embed = ValidatorHelper::arrayEvery(fn ($v) => $v instanceof MessageEmbed, $message);
            $is_array_php = ValidatorHelper::arrayEvery(fn ($v) => !($v instanceof Message), $message);

            if (!$is_message_embed && !$is_array_php) {
                throw new \InvalidArgumentException('Expected an array php or array of MessageEmbed object');
            }

            if ($is_message_embed) {
                $webhook_props['embeds'] = array_map(fn (MessageEmbed $v) => $v->getEmbed(), $message);
            }

            if (!$is_message_embed) {
                $webhook_props = static::sanitizeBody($message);
            }
        }

        if (!empty($options)) {
            $webhook_props = array_merge($webhook_props, $options);
        }


        return $webhook_props;
    }


    /**
     * Sanitize the options
     *
     * @param array $options
     * @return array
     */
    public static function sanitizeOptions(array $options)
    {
        $options_in_includes = ['webhook_id', 'token', 'tts', 'thread_id'];

        return static::sanitize($options, $options_in_includes);
    }

    /**
     * Sanitize the props
     *
     * @param array $props
     * @return void
     */
    public static function sanitizeBody(array $body)
    {
        $body_in_includes = ['content', 'username', 'avatar_url', 'thread_name', 'tts', 'embeds'];

        return static::sanitize($body, $body_in_includes);
    }

    private static function toArray(ResponseInterface $response)
    {
        return json_decode(
            $response->getBody()->getContents(),
            true,
            JSON_OBJECT_AS_ARRAY | JSON_PRESERVE_ZERO_FRACTION
        );
    }

    /**
     * Sanitize values in an array
     *
     * @param array $fields
     * @param array $values
     * @param [type] $flag
     * @return array
     */
    private static function sanitize(array $fields, array $includes, int $flag = ARRAY_FILTER_USE_KEY): array
    {
        $fields = array_filter(
            $fields,
            fn ($field) =>  in_array($field, $includes),
            $flag
        );

        return $fields;
    }
}