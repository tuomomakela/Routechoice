<?php

require_once(dirname(__FILE__).'/Area.class.php');

class Building extends Area
{
	// draw building
	public function draw($image)
	{
		imagefilledpolygon(
			$image,
			$this->_getPointsArray($drawable = true),
			$this->_countPoints(),
			Color::getColor($image, 'black')
		);
		imagefilledpolygon(
			$image,
			$this->_getInnerPointsArray(1.4, $drawable = true),
			$this->_countPoints(),
			Color::getColor($image, 'gray60')
		);
	}
}