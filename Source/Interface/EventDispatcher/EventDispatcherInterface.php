<?php

namespace Neko\Implement;

interface EventDispatcherInterface
{

	public function dispatch(object $event, string $eventName = null);

	public function removeListener(string $eventName, callable $listener);

	public function getListeners($eventName = '');

	public function getListenersCount(?iterable $listeners = array()): int;

	public function hasListener(string $eventName): bool;

	public function addListener(string $eventName, callable $listener): void;

	public function emit(object $event): object | bool;
}
