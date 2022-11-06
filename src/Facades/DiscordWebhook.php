<?php

namespace Marcio1002\DiscordWebhook\Facades;

use Marcio1002\DiscordWebhook\Helpers\Traits\Facades;
use Marcio1002\DiscordWebhook\DiscordWebhook as DiscordWebhookConcrete;
use Marcio1002\DiscordWebhook\Message;
use Marcio1002\DiscordWebhook\MessageEmbed;
use Marcio1002\DiscordWebhook\Contracts\PromiseContract;

use Psr\Http\Message\ResponseInterface;


/**
 *  @method static PromiseContract<ResponseInterface>|ResponseInterface sendMessage(string|array|MessageEmbed|MessageEmbed[]|Message $message, bool $sync = false)
 * 
 * @method static PromiseContract<ResponseInterface>|ResponseInterface editMessage(string $message_id, $message, bool $sync = false)
 * 
 * @method static PromiseContract<ResponseInterface>|ResponseInterface getMessage(string $message_id, bool $sync = false)
 * 
 * @method static PromiseContract<ResponseInterface>|ResponseInterface deleteMessage(string $message_id, bool $sync = false)
 */
class DiscordWebhook
{
    use Facades;

    private static ?DiscordWebhookConcrete $class = null;
    private static array $options = [];


    private function __construct()
    {
    }

    /**
     *
     * @param array $options
     * @return void
     */
    public static function configure(array $options)
    {
        static::$options = $options;

        static::getInstance();
    }

    protected static function getInstance()
    {
        if (empty(static::$class)) {
            static::$class = new DiscordWebhookConcrete(static::$options);
        }

        return static::$class;
    }
}