<?php

declare(strict_types=1);

namespace FoxPHP\EventDispatcher\Listeners;

use FoxPHP\EventDispatcher\Contracts\EventContract;
use FoxPHP\EventDispatcher\Contracts\ListenerContract;

class NullListener implements ListenerContract
{
    public function handle(EventContract $event): mixed
    {
        return $event->getName();
    }
}
