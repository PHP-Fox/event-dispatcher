<?php

declare(strict_types=1);

namespace FoxPHP\EventDispatcher;

use FoxPHP\EventDispatcher\Contracts\DispatcherContract;
use FoxPHP\EventDispatcher\Contracts\EventContract;
use FoxPHP\EventDispatcher\Contracts\ListenerContract;
use InvalidArgumentException;
use ReflectionClass;
use Throwable;

class Dispatcher implements DispatcherContract
{
    /**
     * Dispatcher constructor.
     *
     * @param array $listeners
     * @param array $log
     *
     * @return void
     */
    private function __construct(
        private array $listeners,
        private array $log,
    ) {}

    /**
     * Make a new Dispatcher.
     *
     * @param array $listeners
     *
     * @return static
     */
    public static function make(array $listeners = []): static
    {
        return new static(
            listeners: $listeners,
            log: [],
        );
    }

    /**
     * Add a new Event and Listeners to the dispatcher.
     *
     * @param string $name
     * @param array $listeners
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function add(string $name, array $listeners): void
    {
        foreach ($listeners as $listener) {
            $this->listenerCanBeAdded(listener: $listener);
        }

        $this->listeners[$name] = $listeners;
    }

    /**
     * Append Listeners onto an Event if already registered.
     *
     * @param string $name
     * @param array $listeners
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function append(string $name, array $listeners): void
    {
        $this->checkEvent(name: $name);

        foreach ($listeners as $listener) {
            $this->listenerCanBeAdded(listener: $listener);

            array_push($this->listeners[$name], $listener);
        }
    }

    /**
     * Remove a Listener from an Event.
     *
     * @param string $name
     * @param string $listener
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function remove(string $name, string $listener): void
    {
        $this->checkEvent(name: $name);

        if (! $this->hasListener(event: $name, listener: $listener)) {
            throw new InvalidArgumentException(
                message: "Listener [$listener] has not been registered for event [$name].",
            );
        }

        foreach ($this->getListenersForEvent(name: $name) as $key => $item) {
            if ($item === $listener) {
                unset($this->listeners[$name][$key]);
            }
        }
    }

    /**
     * Remove all Listeners and the Event reference from the Dispatcher.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function removeAll(string $name): void
    {
        $this->checkEvent(name: $name);

        unset($this->listeners[$name]);
    }

    /**
     * CHeck if an Event has been registered.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->listeners);
    }

    /**
     * Check if an Event has a Listener registered.
     *
     * @param string $event
     * @param string $listener
     *
     * @return bool
     */
    public function hasListener(string $event, string $listener): bool
    {
        return in_array($listener, $this->listeners[$event]);
    }

    /**
     * Retrieve all registered Events and their Listeners.
     *
     * @return array
     */
    public function listeners(): array
    {
        return $this->listeners;
    }

    /**
     * Get all registered Listeners for an Event.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    public function getListenersForEvent(string $name): array
    {
        $this->checkEvent(name: $name);

        return $this->listeners[$name];
    }

    /**
     * Dispatch a new Event, and call all registered Listeners.
     *
     * @param EventContract $event
     * @param bool $debug
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function dispatch(EventContract $event, bool $debug = false): void
    {
        $this->checkEvent(name: $event::class);

        foreach ($this->getListenersForEvent(name: $event::class) as $listener) {

            $this->canBeInstantiated(class: $listener);

            /**
             * @var mixed
             */
            $result = (new $listener)->handle(
                event: $event,
            );

            if ($debug) {
                $this->log[$event::class][] = $result;
            }
        }
    }

    /**
     * Return the debug log.
     *
     * @return array
     */
    public function log(): array
    {
        return $this->log;
    }

    /**
     * Internal: Check if an Event has been registered.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function checkEvent(string $name): void
    {
        if (! $this->has(name: $name)) {
            throw new InvalidArgumentException(
                message: "No event has been registered under [$name], please check your config to see why it was not loaded."
            );
        }
    }

    /**
     * Internal: Ensure a Listener implements ListenerContract.
     *
     * @param string $listener
     *
     * @throws \ReflectionException
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function listenerCanBeAdded(string $listener): void
    {
        try {
            $reflector = new ReflectionClass($listener);
        } catch(Throwable $exception) {
            throw $exception;
        }


        if (! $reflector->implementsInterface(ListenerContract::class)) {
            throw new InvalidArgumentException(
                message: "Listeners must implement the ListenerContract, passed in listener, [$listener].",
            );
        }
    }

    /**
     * Internal: Checks to see if a passed in class can be instantiated.
     *
     * @param string $class
     *
     * @throws \ReflectionException
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function canBeInstantiated(string $class): void
    {
        try {
            $reflector = new ReflectionClass($class);
        } catch (Throwable $exception) {
            throw $exception;
        }

        if (! $reflector->isInstantiable()) {
            throw new InvalidArgumentException(
                message: "Passed through class is not an instantiable class [$class]."
            );
        }
    }
}
