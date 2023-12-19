<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP;

class Session 
{
	
	/**
	 * Start session
	 *
	 * @return void
	 */
	public static function start($options = []) 
	{
		if (self::isExtensionLoaded()) 
		{
			session_start ($options);
		}
	}
	
	/**
	 * Check that php session extension is exsits
	 *
	 * @return boolean
	 */
	public static function isExtensionLoaded() 
	{
		if (!extension_loaded('session')) 
		{
			return false;
		}

		return true;
	}

	/**
	 * Get session status code
	 *
	 * @return int
	 */
	public static function getStatus() 
	{
		$status = session_status();

		return $status;
	}

	/**
	 * Get a current session identify
	 *
	 * @return string
	 */
	public static function getId() 
	{
		$sessionId = session_id();

		return $sessionId;
	}

	public static function setId($id)
	{
		return session_id($id);
	}

	public static function setCacheLimiter(string $value) 
	{
		session_cache_limiter($value);
	}

	public static function getCacheLimiter() 
	{
		return session_cache_limiter();
	}

	public static function setCacheExpire(int $value) 
	{
		return session_cache_expire($value);
	}

	public static function abort() 
	{
		return session_abort();
	}

	/**
	 * Check that session id is exists
	 *
	 * @return boolean
	 */
	public static function hasId() 
	{
		if (self::getId() == '') 
		{
			return false;
		}

		return true;
	}

	public static function isActive() 
	{
		if (self::getStatus() == PHP_SESSION_ACTIVE) 
		{
			return false;
		}

		return true;
	}

	/**
	 * Check that session is exists
	 *
	 * @return boolean
	 */
	public static function isExists() :bool 
	{
		if (self::getStatus() == PHP_SESSION_NONE) 
		{
			return false;
		}

		return true;
	}

	/**
	 * Check that session is disabled
	 *
	 * @return boolean
	 */
	public static function isDisabled() :bool 
	{
		if (self::getStatus() == PHP_SESSION_DISABLED) 
		{
			return false;
		}

		return true;
	}

	/**
	 * Check that session is started
	 *
	 * @return boolean
	 */
	public static function isStated() 
	{
		if (!self::isExists() && empty($_SESSION)) 
		{
			return false;
		}

		return true;
	}

	/**
	 * Get a save path of session
	 *
	 * @return bool|string
	 */
	public static function getSavePath() 
	{
		return session_save_path();
	}

	/**
	 * Change save path of session
	 *
	 * @param string $path
	 *
	 * @return string|boolean
	 */
	public static function setSavePath($path = '') 
	{
		return session_save_path($path);
	}

	public static function reset()
	{
		return session_reset();
	}

	public static function getCookieParams()
	{
		return session_get_cookie_params();
	}

	public static function garbageCollect()
	{
		return session_gc();
	}

	public static function createNewId(string $prefix)
	{
		return session_create_id($prefix);
	}

	public static function commit() 
	{
		session_commit();
	}

	public static function regenerateId($use = true) 
	{
		session_regenerate_id($use);
	}

	/**
	 * Change session availability
	 *
	 * @return boolean
	 */
	public static function useCookies() 
	{
		if (ini_get('session.use_cookies')) 
		{
			return true;
		}

		return false;
	}

	/**
	 * Destory session
	 *
	 * @return void
	 */
	public static function destroy() 
	{
		$_SESSION = [];
		session_destroy();
	}

	/**
	 * Set session item
	 *
	 * @param string  $key
	 * @param string  $value
	 * @param boolean $overwrite
	 * @param boolean $valid
	 *
	 * @return boolean
	 */
	public static function set($key, $value, $overwrite = true, $valid = false) :bool 
	{
		$setSessionData = function ($key, $value) 
		{
			$_SESSION[$key] = $value;
		};

		if (isset($_SESSION[$key])) 
		{
			if ($overwrite === true) 
			{
				$setSessionData($key, $value);
			} 
			else 
			{
				return false;
			}
		} 
		else 
		{
			$setSessionData($key, $value);
		}

		if ($valid === true && $_SESSION[$key] !== $value) 
		{
			return false;
		}

		return true;
	}

	/**
	 * Get session item
	 *
	 * @return mixed
	 */
	public static function get($key) 
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}

	/**
	 * Unset all session items
	 *
	 * @return boolean
	 */
	public static function unset() :bool 
	{
		return session_unset();
	}
	
	public static function close() :bool
	{
		return session_write_close();
	}

}
