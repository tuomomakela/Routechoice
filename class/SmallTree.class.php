<?php

require_once(dirname(__FILE__).'/Point.class.php');

class SmallTree extends Point
{
	public function draw($image)
	{
		imagefilledellipse(
			$image,
			$this->x($drawable = true),
			$this->y($drawable = true),
			7.5,
			7.5,
			Color::getColor($image, 'green70')
		);
	}
}
	