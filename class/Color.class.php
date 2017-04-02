<?php

Class Color
{
	private static $initialized = false;
	
	// reference: https://www.tulospalvelu.fi/gps/2016huli4M/map
	private static $colors = array(
		'black' => array(0,0,0),
		'blue70' => array(85,204,221),
		'controls' => array(136,0,152), // (238, 24, 137) / (220, 17, 136)?
		'gray30' => array(204,204,204), // katos (30?)
		'gray60' => array(136,136,136), // building inner
		'green70' => array(83,238,68), // distinct tree
		'green_forbidden' => array(67,136,51),
		'forbidden' => array(179,187,66), // pihamaa
		'pavement' => array(240,220,200), // (250, 237, 219)?
		'route0' => array(64,255,64),
		'route1' => array(255,64,64),
		'temp_forbidden' => array(221,152,253),
		'white' => array(255,255,255),
		'yellow' => array(255,204,119), // field
		'yellow50' => array(255,218,147) // rough open land
		
	);
	
	private static function initialize()
	{
		if (self::$initialized)
			return;
		self::$initialized = true;
	}
	
	public static function getColor($image, $type)
	{
		self::initialize();
		return isset(self::$colors[$type])
			? imagecolorallocate($image,self::$colors[$type][0],self::$colors[$type][1],self::$colors[$type][2])
			: null;
	}	
}


?>