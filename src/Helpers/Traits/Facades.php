<?php

namespace Marcio1002\DiscordWebhook\Helpers\Traits;

/**
 * @method static object handleClass()
 */
trait Facades 
{
    protected static ?object $instance = null;

    public static function __callStatic($method, $args)
    {
        if(empty(static::$instance)) {
            static::$instance = static::handleClass();
        }

        if(!method_exists(static::$instance, $method)) {
            throw new \Exception("Method {$method} not found");
        }

        return static::$instance->$method(...$args);
    }
}