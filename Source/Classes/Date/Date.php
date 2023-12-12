<?php

class Date
{
	
	public static function toUTC($seconds, $format = 'Y-m-d H:i:s')
	{
		$dt = new DateTime('@' . $seconds);
        $dt->setTimezone(new DateTimeZone('UTC'));

        return $dt->format($format);
	}

	public function stringToTime($string, $format) 
	{
		return strtotime($string, $format);
	}
	
	public function getDateArray($date) 
	{
		return getdate($date);
	}
	
	public function parseString($date, $format) 
	{
		return strptime($date, $format);
	}
	
	public function timeToString($date) 
	{
		return strftime($date);
	}
	
	public function getTime() 
	{
		return time();
	}
	
	public function getDayOfWeek($date) 
	{
		$dayofweek = date('w', $date);
		
		return $dayofweek;
	}
	
}
