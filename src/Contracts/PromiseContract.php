<?php

namespace Marcio1002\DiscordWebhook\Contracts;

use React\Promise\PromiseInterface;

/**
 * 
 * 
 * @method PromiseContract then(callable $onFulfilled = null, callable $onRejected = null)
 */
interface PromiseContract extends PromiseInterface
{
    /**
     * 
     *
     * @param callable $onRejected
     * @return PromiseContract
     */
    public function otherwise(callable $onRejected);
}