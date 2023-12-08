<?php

declare(strict_types=1);

namespace Xanax\Classes\Event;

use Xanax\Implement\EventDispatcherInterface;

use Xanax\Classes\Event\Instance as EventInstance;

class Dispatcher implements EventDispatcherInterface
{
	private $listeners = [];

	/**
	 * Dispatch registered event with event name
	 * 
	 * @String event
	 * @String eventName
	 */
	public function Dispatch($event, string $eventName = null)
	{
		if (\is_object($event)) 
		{
			$eventName = $eventName ?? \get_class($event);
		}

		if (!$eventName) 
		{
			$eventName = $event;
			$event     = new EventInstance();
		}

		$listeners = $this->getListeners($eventName);

		if ($listeners) 
		{
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
		foreach ($listeners as $listener) 
		{
			$listener($event, $eventName, $this);
		}
	}

	/**
	 * Remove listener from matched by event name
	 * 
	 * @String eventName
	 * @Callable Listener
	 */
	public function removeListener(string $eventName, callable $listener)
	{
		if (!$this->hasListener($eventName)) 
		{
			return false;
		}
	}

	public function getListeners($eventName = '')
	{
		return isset($eventName) ? $this->listeners[$eventName] : $this->listeners;
	}

	/**
	 * Get count of listeners
	 * 
	 * @Iterable listeners
	 */
	public function getListenersCount(?iterable $listeners = array()) :int
	{
		return count($listeners || $this->getListeners());
	}

	/**
	 * Check that listener is registered
	 * 
	 * @String eventName
	 */
	public function hasListener(string $eventName) :bool
	{
		if ($eventName !== null) 
		{
			$listener = $this->getListeners($eventName);

			return !empty($listener);
		}

		if ($this->getListenersCount() <= 0) 
		{
			return false;
		}

		foreach ($this->getListeners() as $listenerItem) 
		{
			if ($listenerItem) 
			{
				return true;
			}
		}

		return false;
	}

	public function addListener(string $eventName, callable $listener) :void
	{
		if (isset($this->listeners[$eventName])) 
		{
			$this->listeners[$eventName][] = $listener;
		}
		else
		{
			$this->listeners[$eventName] = [$listener];
		}
	}

	public function Emit(object $event) :object
	{
		$listeners = $this->getListeners(get_class($event)) ?? [];

		if (count($listeners) <= 0) 
		{
			//return false;
		}

		foreach ($listeners as $listener) 
		{
			$listener($event);
		}

		return $event;
	}
}
