<?php

namespace Marcio1002\DiscordWebhook\Helpers\Traits;

trait Format
{

    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Returns the JSON representation of the embed
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * Returns an object's properties in an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $toArray = [];

        foreach ($this as $key => $value) {
            $toArray[$key] = $value;
        }

        return $toArray;
    }

    /**
     * Sanitize values in an array
     *
     * @param array $fields
     * @param array $values
     * @param [type] $flag
     * @return array
     */
    public function sanitize(array $fields, array $values, $flag = ARRAY_FILTER_USE_KEY): array
    {
        $options = array_filter(
            $values,
            fn ($field) =>  in_array($field, $fields),
            $flag
        );

        return $options;
    }
}