<?php

namespace Marcio1002\DiscordWebhook;

use InvalidArgumentException;
use
    Marcio1002\DiscordWebhook\Helpers\Traits\Format,
    Marcio1002\DiscordWebhook\Helpers\Validator;

class Message
{
    use Format;

    /**
     * 
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setAvatar(string $avatar): self
    {
        if (!Validator::isURL($avatar)) {
            throw new \InvalidArgumentException('Avatar must be a valid URL');
        }

        $this->avatar_url = $avatar;

        return $this;
    }

    /**
     * Adds a embed(s) to the message
     *
     * @param Marcio1002\DiscordWebhook\MessageEmbed|Marcio1002\DiscordWebhook\MessageEmbed[] $embeds
     * @return self
     */
    public function setEmbeds($embeds): self
    {
        if (
            !($embeds instanceof MessageEmbed) &&
            (is_array($embeds) && !Validator::arrayEvery(fn ($v) => $v instanceof MessageEmbed, $embeds))
        ) {
            throw new InvalidArgumentException('Expected an object or array of MessageEmbed object');
        }

        if (is_array($embeds)) {
            $embeds = array_slice($embeds, 0, 10);
            $this->embeds = array_map(fn (MessageEmbed $v) => $v->getEmbed(), $embeds);
        } else {
            $this->embeds = [$embeds->getEmbed()];
        }

        return $this;
    }

    /**
     * Add a component(s) to the message
     * @deprecated version 1.1.1
     * @param Marcio1002\DiscordWebhook\Component|Marcio1002\DiscordWebhook\Component[] $component
     * @return self
     */
    public function setComponent($component): self
    {
        $this->components = is_array($component) ?
            array_map(fn (Component $c) => $c->toArray(), $component) :
            $component->toArray();

        return $this;
    }
}