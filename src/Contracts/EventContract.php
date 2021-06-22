<?php

declare(strict_types=1);

namespace FoxPHP\EventDispatcher\Contracts;

interface EventContract
{
    public function getName(): string;
}
