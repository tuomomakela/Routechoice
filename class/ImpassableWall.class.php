<?php

require_once(dirname(__FILE__).'/Line.class.php');

class ImpassableWall extends Line
{
	public function draw($image)
	{
		$drawable = true;
		
		imagesetthickness($image, 4);
		
		for ($i = 1; $i < $this->_countPoints(); ++$i)
		{
			imageline(
				$image,
				$this->getPoint($i-1)->x($drawable),
				$this->getPoint($i-1)->y($drawable),
				$this->getPoint($i)->x($drawable),
				$this->getPoint($i)->y($drawable),
				Color::getColor($image, 'black')
			);
		}
	}
}
	