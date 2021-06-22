<?php

use FoxPHP\EventDispatcher\Contracts\DispatcherContract;
use FoxPHP\EventDispatcher\Dispatcher;
use FoxPHP\EventDispatcher\Events\NullEvent;
use FoxPHP\EventDispatcher\Listeners\NullListener;
use FoxPHP\EventDispatcher\Tests\Stubs\CannotInstantiate;
use FoxPHP\EventDispatcher\Tests\Stubs\FailableClass;

it('can create our dispatcher with an empty array.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [],
    );

    expect(
        value: $dispatcher,
    )->toBeInstanceOf(DispatcherContract::class);

    expect(
        value: $dispatcher->listeners(),
    )->toBeEmpty()->toEqual([]);
});

it('can register listeners when creating our dispatcher.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [
            NullEvent::class => [
                NullListener::class
            ]
        ]
    );

    expect(
        value: $dispatcher->listeners(),
    )->toHaveCount(1)->toEqual([
        NullEvent::class => [
            NullListener::class
        ]
    ]);
});

it('can add events to the dispatcher.', function () {
    $dispatcher = Dispatcher::make();

    $dispatcher->add(
        name: NullEvent::class,
        listeners: [
            NullListener::class
        ],
    );

    expect(
        value: $dispatcher->listeners()
    )->toHaveCount(1)->toEqual([
        NullEvent::class => [
            NullListener::class
        ]
    ]);

    expect(
        value: $dispatcher->has(name: NullEvent::class),
    )->toBeTrue();
});

it('can append listeners to a registered event.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [
            NullEvent::class => [],
        ]
    );

    expect(
        value: $dispatcher->has(name: NullEvent::class),
    )->toBeTrue();

    expect(
        value: $dispatcher->getListenersForEvent(
            name: NullEvent::class,
        ),
    )->toBeEmpty()->toEqual([]);

    $dispatcher->append(
        name: NullEvent::class,
        listeners: [
            NullListener::class,
        ]
    );

    expect(
        value: $dispatcher->getListenersForEvent(
        name: NullEvent::class,
    ),
    )->toHaveCount(1)->toContain(NullListener::class);
});

it('can remove a listener from an event.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [
            NullEvent::class => [
                NullListener::class,
            ],
        ]
    );

    expect(
        value: $dispatcher->listeners(),
    )->toHaveCount(1)->toEqual([
        NullEvent::class => [
            NullListener::class,
        ],
    ]);

    $dispatcher->remove(
        name: NullEvent::class,
        listener: NullListener::class,
    );

    expect(
        value: $dispatcher->listeners(),
    )->toHaveCount(1)->toEqual([
        NullEvent::class => []
    ]);
});

it('can remove all listeners for an event, and the event itself.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [
            NullEvent::class => [
                NullListener::class,
            ],
        ]
    );

    expect(
        value: $dispatcher->listeners(),
    )->toHaveCount(1)->toEqual([
        NullEvent::class => [
            NullListener::class,
        ],
    ]);

    $dispatcher->removeAll(name: NullEvent::class);

    expect(
        value: $dispatcher->listeners(),
    )->toHaveCount(0)->toEqual([])->toBeEmpty();
});

it('can check if an event has a listener registered.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [
            NullEvent::class => [
                NullListener::class,
            ],
        ]
    );

    expect(
        value: $dispatcher->listeners(),
    )->toHaveCount(1)->toEqual([
        NullEvent::class => [
            NullListener::class,
        ],
    ]);

    expect(
        value: $dispatcher->hasListener(
            event: NullEvent::class,
            listener: NullListener::class,
        ),
    )->toBeTrue();

    expect(
        value: $dispatcher->hasListener(
        event: NullEvent::class,
        listener: FailableClass::class,
    ),
    )->toBeFalse();
});

it('throws an InvalidArgumentException if an event has not been registered trying to retrieve the listeners for the event.', function () {
    $dispatcher = Dispatcher::make();

    expect(
        value: $dispatcher->listeners()
    )->toBeEmpty();

    $dispatcher->getListenersForEvent(name: NullEvent::class);
})->throws(
    exceptionClass: InvalidArgumentException::class,
);

it('throws an InvalidArgumentException if trying to remove an event that has not been registered.', function () {
    $dispatcher = Dispatcher::make();

    expect(
        value: $dispatcher->listeners()
    )->toBeEmpty();

    $dispatcher->removeAll(name: NullEvent::class);
})->throws(
    exceptionClass: InvalidArgumentException::class,
);

it('throws an InvalidArgumentException if trying to remove a listener that has not been registered for an event.', function () {
    $dispatcher = Dispatcher::make();

    $dispatcher->remove(
        name: NullEvent::class,
        listener: NullListener::class,
    );
})->throws(
    exceptionClass: InvalidArgumentException::class
);

it('will dispatch events and listeners will be called.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [
            NullEvent::class => [
                NullListener::class,
            ],
        ]
    );

    $dispatcher->dispatch(
        event: new NullEvent(),
        debug: true,
    );

    expect(
        value: $dispatcher->log(),
    )->toHaveCount(1)->toContain(['null-event']);
});

it('throws an InvalidArgumentException if the Listener passed in implements ListenerContract on add.', function () {
    $dispatcher = Dispatcher::make();

    $dispatcher->add(
        name: NullEvent::class,
        listeners: [
            FailableClass::class,
        ],
    );
})->throws(
    exceptionClass: InvalidArgumentException::class,
);

it('throws an InvalidArgumentException if the Listener passed in implements ListenerContract on append.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [
            NullEvent::class => []
        ]
    );

    $dispatcher->append(
        name: NullEvent::class,
        listeners: [
            FailableClass::class,
        ],
    );
})->throws(
    exceptionClass: InvalidArgumentException::class,
);

it('throws an InvalidArgumentException if the passed in listener cannot be instantiated.', function () {
    $dispatcher = Dispatcher::make(
        listeners: [
            NullEvent::class => [
                CannotInstantiate::class,
            ]
        ]
    );

    $dispatcher->dispatch(
        event: new NullEvent(),
    );
})->throws(
    exceptionClass: InvalidArgumentException::class
);

