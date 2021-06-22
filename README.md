# Event Dispatcher

A simple event dispatcher in PHP.

## Installation

To install this package you can use composer:

```bash
composer require foxphp/event-dispatcher
```

## Usage

```php
// Create a new dispatcher

$dispatcher = Dispatcher::make(
    listeners: [
        Path\To\Event::class => [
            Path\To\Listener::class,
        ]
    ]
);

$dispatcher = new Dispatcher(
    listeners: [],
    log: []
);

// Add event with listeners
$dispatcher->add(
    event: Path\To\Event::class,
    listeners: [
        Path\To\Listener::class,
    ]
);

// Append listeners onto an event listener array
$dispatcher->append(
    event: Path\To\Event::class,
    listeners: [
        Path\To\Another\Listener::class,
    ]
);

// Dispatch
$dispatcher->dispatch(
    event: new Path\To\Event(),
    debug: true,
);

// Get debug log items
$dispatcher->log(); // []
```
