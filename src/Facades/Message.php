<?php

namespace Marcio1002\DiscordWebhook\Facades;

use Marcio1002\DiscordWebhook\Helpers\Traits\Facades;
use Marcio1002\DiscordWebhook\Message as DiscordWebhookMessage;

class Message
{
    use Facades;

    public static function getInstance()
    {
        return new DiscordWebhookMessage();
    }
}