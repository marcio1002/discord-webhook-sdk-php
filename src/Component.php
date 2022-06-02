<?php

namespace Marcio1002\DiscordWebhook;

use
    Marcio1002\DiscordWebhook\Helpers\Traits\Format,
    Marcio1002\DiscordWebhook\Helpers\Validator;


/**
 * @deprecated since version 1.1.1
 * Discord not implemented in webhooks
 */
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

        $has_line_action = function ($line) {
            $type = $line['type'] ?? null;
            return (is_array($line) && $type === 1) ? true : false;
        };

        $is_add_line_action = Validator::arrayEvery($has_line_action, $this->components);

        if ($is_add_line_action && count($this->components) <= 5) {
            $this->components[] = [
                'type' => 1,
                'components' => [],
            ];
        }


        return $this;
    }

    /**
     * Add button component
     * 
     * - label, style, emoji, custom_id or url, disabled
     * - emoji = id, name, animated
     *
     * @param array $button
     * @return self
     */
    public function addButton(array $button): self
    {
        $fields = ['label', 'style', 'emoji', 'custom_id', 'url', 'disabled'];

        $fields_emoji = ['id', 'name', 'animated'];

        $button = $this->sanitize($fields, $button);

        in_array('emoji', $button) && ($button['emoji'] = $this->sanitize($fields_emoji, $button['emoji']));

        $button['type'] = 2;

        $this->addInLineAction($button);

        return $this;
    }

    /**
     * Add select component
     * - custom_id, label, options, placeholder, min_values, max_values, disabled
     * - options = label, value, description, emoji, disabled
     * - emoji = id, name, animated
     *
     * @param array $select
     * @return self
     */
    public function addSelect(array $select): self
    {
        $fields = ['custom_id', 'label', 'options', 'placeholder', 'min_values', 'max_values', 'disabled'];

        $fields_options = ['label', 'value', 'description', 'emoji', 'default'];
        $fields_emoji = ['id', 'name', 'animated'];

        $select = $this->sanitize($fields, $select);

        in_array('options', $select) && ($select['options'] = $this->sanitize($fields_options, $select['options']));

        $select['type'] = 3;

        $this->addInLineAction($select);

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

        $this->addInLineAction($input);

        return $this;
    }


    public function toArray(): array
    {
        return $this->components;
    }

    private function addInLineAction($component): void
    {
        foreach ($this->components as &$line) {
            if (
                is_array($line) &&
                isset($line['components']) &&
                count($line['components']) <= 5
            )
                array_push($line['components'], $component);
        }
    }
}