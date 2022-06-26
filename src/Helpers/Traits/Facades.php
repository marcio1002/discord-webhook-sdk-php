<?php

namespace Marcio1002\DiscordWebhook\Helpers\Traits;

/**
 * @method static object handleClass()
 */
trait Facades 
{
    public static function __callStatic($method, $args)
    {
        $instance = static::handleClass();

        if(!method_exists($instance, $method)) {
            throw new \Exception("Method {$method} not found in class " . get_class($instance));
        }

        return $instance->$method(...$args);
    }
}