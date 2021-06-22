<?php

declare(strict_types=1);

namespace FoxPHP\EventDispatcher\Contracts;

interface DispatcherContract
{
    /**
     * Make a new Dispatcher.
     *
     * @param array $listeners
     *
     * @return static
     */
    public static function make(array $listeners = []): static;

    /**
     * Add an Event with its Listeners.
     *
     * @param string $name
     * @param array $listeners
     *
     * @return void
     */
    public function add(string $name, array $listeners): void;

    /**
     * Appened a series of Listeners into the Events listeners array.
     *
     * @param string $name
     * @param array $listeners
     *
     * @return void
     */
    public function append(string $name, array $listeners): void;

    /**
     * Remove a specific Listener from the events array for an Event.
     *
     * @param string $name
     * @param string $listener
     *
     * @param void
     */
    public function remove(string $name, string $listener): void;

    /**
     * Remove all Listeners and the Event for the passed in Event.
     *
     * @param string $name
     *
     * @return void
     */
    public function removeAll(string $name): void;

    /**
     * Check if the passed in Event name has been registered.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Check if the passed in Listener has been registered for the passed in Event.
     *
     * @param string $event
     * @param string $listener
     *
     * @return bool
     */
    public function hasListener(string $event, string $listener): bool;

    /**
     * Return all Listeners registered on the Dispatcher.
     *
     * @return array
     */
    public function listeners(): array;

    /**
     * Return all Listeners registered against a passed in Event.
     *
     * @param string $name
     *
     * @return array
     */
    public function getListenersForEvent(string $name): array;

    /**
     * Dispatch an event with its registered Listeners.
     *
     * @param EventContract $event
     * @param bool $debug
     *
     * @return void
     */
    public function dispatch(EventContract $event, bool $debug = false): void;

    /**
     * Return the debug log.
     *
     * @return array
     */
    public function log(): array;
}
