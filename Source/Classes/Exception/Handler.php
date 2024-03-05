<?php

declare(strict_types=1);

namespace Clover\Classes\Exception;

class Handler
{

	public static function setError(callable $callback)
	{
		$previous = self::setErrorHandler(function ($errorRaised, $errorMessage, $fileName, $lineNumber, $context) use (&$previous, $callback) {
			if ($previous && is_callable($callback)) {
				$callback($errorRaised, $errorMessage, $fileName, $lineNumber, $context);
			} else {
				return false;
			}
		});
	}

	public static function setErrorHandler($callback, $error_level = E_ALL)
	{
		return set_error_handler($callback, $error_level);
	}

	public static function registerShutdownFunction($callback, ...$args): void
	{
		register_shutdown_function($callback, ...$args);
	}

	public static function clearLastError(): void
	{
		error_clear_last();
	}

	public static function getLastError(): array
	{
		return error_get_last();
	}

	public static function setExceptionHandler(callable $exceptionFunction)
	{
		set_exception_handler($exceptionFunction);
	}

	/**
	 * Generates a backtrace
	 */
	public static function generatesBacktrace(int $options = DEBUG_BACKTRACE_PROVIDE_OBJECT, int $limit = 0)
	{
		return debug_backtrace($options, $limit);
	}

	public static function restorePreviousErrorStack()
	{
		restore_error_handler();
	}

	public static function restorePreviousExceptionStack()
	{
		restore_exception_handler();
	}

	public static function trigger($errorMessage)
	{
		trigger_error($errorMessage);
	}
}
