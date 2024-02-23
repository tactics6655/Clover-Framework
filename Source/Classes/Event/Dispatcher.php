<?php

declare(strict_types=1);

namespace Neko\Classes\Event;

use Neko\Implement\EventDispatcherInterface;

use Neko\Classes\Event\Instance as EventInstance;

class Dispatcher implements EventDispatcherInterface
{
	private $listeners = [];

	/**
	 * Dispatch registered event with event name
	 * 
	 * @param object event
	 * @param string eventName
	 */
	public function dispatch(object $event, string $eventName = null)
	{
		if (is_object($event)) {
			$eventName = $eventName ?? get_class($event);
		}

		if (!$eventName) {
			$eventName = $event;
			$event     = new EventInstance();
		}

		$listeners = $this->getListeners($eventName);

		if ($listeners) {
			$this->callListeners($listeners, $eventName, $event);
		}
	}

	/**
	 * Call listeners
	 * 
	 * @Iterable listeners
	 * @String eventName
	 * @Object event
	 */
	protected function callListeners(iterable $listeners, string $eventName, object $event)
	{
		foreach ($listeners as $listener) {
			$listener($event, $eventName, $this);
		}
	}

	/**
	 * Remove listener from matched by event name
	 * 
	 * @String $eventName
	 * @Callable $caller
	 */
	public function removeListener(string $eventName, callable $caller)
	{
		if (!$this->hasListener($eventName)) {
			return false;
		}

		$key = array_search($caller, $this->listeners[$eventName]);

		if ($key === false) {
			return false;
		}

		unset($this->listeners[$eventName][$key]);

		return true;
	}

	public function getListeners($eventName = '')
	{
		return isset($this->listeners[$eventName]) ? $this->listeners[$eventName] : $this->listeners;
	}

	/**
	 * Get count of listeners
	 * 
	 * @Iterable listeners
	 */
	public function getListenersCount(?iterable $listeners = array()): int
	{
		return count($listeners ?? $this->getListeners());
	}

	/**
	 * Check that listener is registered
	 * 
	 * @String eventName
	 */
	public function hasListener(string $eventName): bool
	{
		if ($eventName !== null) {
			$listener = $this->getListeners($eventName);

			return !empty($listener);
		}

		if ($this->getListenersCount() <= 0) {
			return false;
		}

		foreach ($this->getListeners() as $listenerItem) {
			if ($listenerItem) {
				return true;
			}
		}

		return false;
	}

	public function addListener(string $eventName, callable $listener): void
	{
		if (isset($this->listeners[$eventName])) {
			$this->listeners[$eventName][] = $listener;
		} else {
			$this->listeners[$eventName] = [$listener];
		}
	}

	public function emit(object $event): object | bool
	{
		$listeners = $this->getListeners(get_class($event)) ?? [];

		if (count($listeners) <= 0) {
			return false;
		}

		foreach ($listeners as $listener) {
			$listener($event);
		}

		return $event;
	}
}
