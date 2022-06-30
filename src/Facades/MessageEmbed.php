<?php

namespace Marcio1002\DiscordWebhook\Facades;

use Marcio1002\DiscordWebhook\Helpers\Traits\Facades;
use Marcio1002\DiscordWebhook\MessageEmbed as DiscordWebhookMessageEmbed;

class MessageEmbed
{
    use Facades;

    public static function getInstance()
    {
        return new DiscordWebhookMessageEmbed();
    }
}