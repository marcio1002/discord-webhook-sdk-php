<?php

namespace Marcio1002\DiscordWebhook;

use
    Marcio1002\DiscordWebhook\Helpers\Traits\Format;

class Component
{
    use Format;

    const PRIMARY = 1;
    const SECONDARY = 2;
    const SUCCESS = 3;
    const DANGER = 4;
    const LINK = 5;

    const SHORT = 1;
    const PARAGRAPH = 2;

    private array $components = [];


    /**
     * Adds lines to the component
     *
     * @return self
     */
    public function addLineAction(): self
    {
        $this->components[] = [
            'type' => 1,
            'components' => [],
        ];

        return $this;
    }

    /**
     * Add button component
     * 
     * - label, style, emoji, custom_id or url, disabled
     *
     * @param array $button
     * @return self
     */
    public function addButton(array $button): self
    {
        $fields = ['label', 'style', 'emoji', 'custom_id', 'url', 'disabled'];

        $button = $this->sanitize($fields, $button);

        $button['type'] = 2;

        $this->addComponentLine($button);

        return $this;
    }

    /**
     * Add select component
     * - custom_id, label, options, placeholder, min_values, max_values, disabled
     * - options = label, value, description, emoji, disabled
     *
     * @param array $select
     * @return self
     */
    public function addSelect(array $select): self
    {
        $fields = ['custom_id', 'label', 'options', 'placeholder', 'min_values', 'max_values', 'disabled'];

        $fields_options = ['label', 'value', 'description', 'emoji', 'default'];

        $select = $this->sanitize($fields, $select);

        in_array('options', $select) && ($select['options'] = $this->sanitize($fields_options, $select['options']));

        $select['type'] = 3;

        $this->addComponentLine($select);

        return $this;
    }

    /**
     * Add input text component
     *
     * - custom_id, label, placeholder, style, value, min_length, max_length, required
     * 
     * @param array $input
     * @return self
     */
    public function addInputText(array $input): self
    {
        $fields = ['custom_id', 'label', 'placeholder', 'style', 'value', 'min_length', 'max_length', 'required'];

        $input = $this->sanitize($fields, $input);

        $input['type'] = 4;

        $this->addComponentLine($input);

        return $this;
    }


    public function toArray(): array
    {
        return $this->components;
    }

    private function addComponentLine($component): void
    {
        foreach ($this->components as &$line) {
            if (is_array($line) && count($line) >= 0 && count($line) <= 5)
                array_push($line['components'], $component);
        }
    }
}