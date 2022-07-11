<?php

namespace Marcio1002\DiscordWebhook\Facades;

use Marcio1002\DiscordWebhook\Helpers\Traits\Facades;
use Marcio1002\DiscordWebhook\DiscordWebhook as DiscordWebhookConcrete;

class DiscordWebhook
{

    private static ?DiscordWebhookConcrete $class = null;
    private static array $options = [];

    use Facades;

    private function __construct() {}

    protected static function getInstance()
    {
        if(empty(static::$class)) {
            static::$class = new DiscordWebhookConcrete(static::$options);
        }

        return static::$class;
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
}