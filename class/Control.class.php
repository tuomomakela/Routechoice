<?php

require_once(dirname(__FILE__).'/Point.class.php');

class Control extends Point
{
	public function draw($image)
	{
		for ($i = -1; $i <= 1; ++$i)
		{
			imageellipse(
				$image,
				$this->x($drawable = true),
				$this->y($drawable = true),
				60+$i,
				60+$i,
				Color::getColor($image, 'controls')
			);
		}
		
	}
}
	