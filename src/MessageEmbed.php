<?php

namespace Marcio1002\DiscordWebhook;

use DateTime;

class MessageEmbed
{
    private array $embed = [];

    public function __toString()
    {
        return $this->toJson();
    }

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
        if (!$this->isURL($avatar)) {
            throw new \InvalidArgumentException('Avatar must be a valid URL');
        }

        $this->avatar = $avatar;

        return $this;
    }


    /**
     * Adds a title to the embed
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->embed['title'] = $title;
        return $this;
    }

    /**
     * Adds a author to the embed
     *
     * @param string $name
     * @param string $url
     * @param string $icon
     * @return void
     */
    public function setAuthor(string $name, string $url, string $icon): self
    {
        if (!$this->isURL($url)) {
            throw new \InvalidArgumentException('Invalid URL of property url');
        }

        if (!$this->isURL($icon)) {
            throw new \InvalidArgumentException('Invalid URL of property icon');
        }

        $this->embed['author'] = [
            'name' => $name,
            'url' => $url,
            'icon_url' => $icon
        ];

        return $this;
    }

    /**
     * Adds a description to the embed
     *
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->embed['description'] = $description;
        return $this;
    }

    /**
     * Adds the date and time of the message sent
     * @param string|int|\Datetime $timestamp
     */
    public function setTimesTamp($timestamp = null): self
    {
        if (is_null($timestamp)) {
            $timestamp = date('Y-m-d H:i:s');
        }

        if ($timestamp instanceof \Datetime) {
            $timestamp = $timestamp->format('Y-m-d H:i:s');
        }

        $this->embed['timestamp'] = $timestamp;
        return $this;
    }

    /**
     * Adds a color to the embed
     *
     * @param string $color
     * @return self
     */
    public function setColor(string $color): self
    {
        if (preg_match("/^#[a-fA-F0-9]{3,6}$/", $color)) {
            $color = hexdec(str_replace('#', '', $color));
        }

        if(preg_match("//", $color)) {

        }

        $this->embed['color'] = $color;
        return $this;
    }


    /**
     * Adds a image to the embed
     *
     * @param string $text
     * @param string $icon
     * @return self
     */
    public function setImage(string $image, int $height = 0, int $width = 0): self
    {
        if (!$this->isURL($image)) {
            throw new \InvalidArgumentException('Invalid URL of property image');
        }

        $this->embed['image'] = [
            'url' => $image,
            'height' => $height,
            'width' => $width
        ];

        return $this;
    }

    /**
     * Sets the URL of this embed
     *
     * @param string $url
     * @return self
     */
    public function setUrl(string $url): self
    {
        if (!$this->isURL($url)) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        $this->embed['url'] = $url;
        return $this;
    }

    /**
     * Adds a thumbnail to the embed
     *
     * @param string $thumbnail
     * @return self
     */
    public function setThumbnail(string $thumbnail)
    {
        if (!$this->isURL($thumbnail)) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        $this->embed['thumbnail'] = $thumbnail;

        return $this;
    }

    /**
     * Adds a fields to the embed
     *
     * @param string $name
     * @param string $value
     * @param boolean $inline
     * @return void
     */
    public function setField(string $name, string $value, bool $inline = false)
    {
        $this->embed['fields'][] = [
            'name' => $name,
            'value' => $value,
            'inline' => $inline
        ];

        return $this;
    }

    /**
     * Adds a fields to the embed
     *
     * @param array[ name => string, value => string, inline => bool] ...$fields
     * @return self
     */
    public function setFields(...$fields): self
    {
        $this->embed['fields'] = $fields;
        return $this;
    }

    public function spliceFields(int $index, int $length, ...$fields): self
    {
        if (in_array('fields', $this->embed)) {
            array_splice($this->embed['fields'], $index, $length, $fields);
        }

        return $this;
    }


    /**
     * Filter the fields according to the callback result
     * @param callable|\Closure $callback
     */
    public function filterFields($callback): self
    {
        if (!is_callable($callback) && !($callback instanceof \Closure)) {
            throw new \InvalidArgumentException('Invalid callback');
        }

        if (in_array('fields', $this->embed)) {
            $this->embed['fields'] = array_filter($this->embed['fields'], $callback, ARRAY_FILTER_USE_BOTH);
        }

        return $this;
    }


    /**
     * Adds a footer to the embed
     *
     * @param string $name
     * @param string $icon
     * @return self
     */
    public  function setFooter(string $name, string $icon): self
    {
        if (!$this->isURL($icon)) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        $this->embed['footer'] = [
            'name' => $name,
            'icon_url' => $icon
        ];

        return $this;
    }


    /**
     * Returns the JSON representation of the embed
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->getEmbed(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * Returns the array representation of the embed
     *
     * @return array
     */
    public function getEmbed(): array
    {
        return $this->embed;
    }

    public function toArray(): array
    {
        $toArray = [];

        foreach ($this as $key => $value) {
            $toArray[$key] = $value;
        }

        return $toArray;
    }

    /**
     * Create a random color
     *
     * @return string
     */
    public static function randomColor(bool $hexadecimal = false): string
    {
        if ($hexadecimal) {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        return (string) mt_rand(0, 0xFFFFFF);
    }

    private function isURL(string $uri): bool
    {
        return filter_var($uri, FILTER_VALIDATE_URL) ?  true : false;
    }
}