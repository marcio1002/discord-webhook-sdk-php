<?php

namespace Marcio1002\DiscordWebhook;

use
    Marcio1002\DiscordWebhook\Helpers\Traits\Format,
    Marcio1002\DiscordWebhook\Helpers\Validator;

class MessageEmbed
{
    use Format;

    private array $embed = [];


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
        if (!Validator::isURL($url)) {
            throw new \InvalidArgumentException('Invalid URL of property url');
        }

        if (!Validator::isURL($icon)) {
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
     * @return self
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
     * @example $color set color code hex, rgb or decimal
     * 
     * setColor('#363636') 
     * setColor('rgb(255, 255, 133)')
     * setColor(16761029)
     *
     * @param string $color
     * @return self
     */
    public function setColor(string $color): self
    {
        if (preg_match("/^#[a-fA-F0-9]{3,6}$/", $color)) {
            $color = hexdec(str_replace('#', '', $color));
        }

        if (preg_match("/^rgb[a]?/", $color)) {
            $color = preg_replace('/[^\d,]+/', '', $color);
            $color = explode(',', $color);

            if (count($color) < 3 || count($color) > 3) {
                throw new \InvalidArgumentException('Invalid rgb color format');
            }

            $color = array_map('trim', $color);

            [$r, $g, $b] = $color;
            $color = ($r << 16) + ($g << 8) + $b;
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
        if (!Validator::isURL($image) && !Validator::isValidImage($image)) {
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
        if (!Validator::isURL($url)) {
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
        if (!Validator::isURL($thumbnail)) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        $this->embed['thumbnail'] = [
            'url' => $thumbnail
        ];

        return $this;
    }

    /**
     * Adds a fields to the embed
     *
     * @param string $name
     * @param string $value
     * @param boolean $inline
     * @return self
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
     * @param int @mode
     * @return self
     */
    public function filterFields($callback, int $mode = 0): self
    {
        if (!is_callable($callback) && !($callback instanceof \Closure)) {
            throw new \InvalidArgumentException('Invalid callback');
        }

        if (in_array('fields', $this->embed)) {
            $this->embed['fields'] = array_filter($this->embed['fields'], $callback, $mode);
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
        if (!Validator::isURL($icon)) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        $this->embed['footer'] = [
            'name' => $name,
            'icon_url' => $icon
        ];

        return $this;
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
}