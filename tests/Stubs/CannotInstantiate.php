<?php

declare(strict_types=1);

namespace FoxPHP\EventDispatcher\Tests\Stubs;

use FoxPHP\EventDispatcher\Contracts\EventContract;
use FoxPHP\EventDispatcher\Contracts\ListenerContract;

class CannotInstantiate implements ListenerContract
{
    private function __construct(
        private string $name,
    ) {}

    public static function make(string $name): static
    {
        return new static(
            name: $name
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function handle(EventContract $event): string
    {
        return $event->getName();
    }
}
