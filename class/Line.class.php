<?php

class Line
{
	private $points = array();
	
	public function __construct($points = array())
	{
		if (!empty($points))
		{
			foreach ($points AS $point)
			{
				$this->points[] = new Point($point[0], $point[1]);
			}
		}
	}
	protected function _countPoints()
	{
		return count($this->points);
	}
	protected function _getPointsArray($drawable = true)
	{
		$arr = array();
		foreach ($this->points AS $point)
		{
			$arr[] = $point->x($drawable);
			$arr[] = $point->y($drawable);
		}
		return $arr;
	}
	public function getPoint($id)
	{
		return $this->points[$id];
	}
	public function getPoints()
	{
		return $this->points;
	}
}
