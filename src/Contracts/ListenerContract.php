<?php

declare(strict_types=1);

namespace FoxPHP\EventDispatcher\Contracts;

interface ListenerContract
{
    public function handle(EventContract $event): mixed;
}