<?php

require_once(dirname(__FILE__).'/Point.class.php');

class ManMadeFeature extends Point
{
	public function draw($image)
	{
		$size = 12;
		$drawable = true;
		
		imagesetthickness($image, 2.2);
		
		imageline(
			$image,
			$this->x($drawable) - $size / 2,
			$this->y($drawable) - $size / 2,
			$this->x($drawable) + $size / 2,
			$this->y($drawable) + $size / 2,
			Color::getColor($image, 'black')
		);
		imageline(
			$image,
			$this->x($drawable) - $size / 2,
			$this->y($drawable) + $size / 2,
			$this->x($drawable) + $size / 2,
			$this->y($drawable) - $size / 2,
			Color::getColor($image, 'black')
		);
	}
}
	