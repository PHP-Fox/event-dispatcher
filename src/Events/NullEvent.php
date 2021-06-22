<?php

declare(strict_types=1);

namespace FoxPHP\EventDispatcher\Events;

use FoxPHP\EventDispatcher\Contracts\EventContract;

class NullEvent implements EventContract
{

    public function getName(): string
    {
        return 'null-event';
    }
}