<?php

declare(strict_types=1);

namespace FoxPHP\EventDispatcher\Tests\Stubs;

use FoxPHP\EventDispatcher\Contracts\EventContract;

class FailableClass
{
    public function handle(EventContract $event): string
    {
        return $event->getName();
    }
}
