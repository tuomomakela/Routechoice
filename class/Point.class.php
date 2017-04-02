<?php

class Point
{
	private $original_position = array(
		'x' => null,
		'y' => null
	);
	private $position = array(
		'x' => null,
		'y' => null
	);
	
	// angle 0 is up, pi/2 is right...
	public function __construct($x, $y, $angle = null, $distance = null)
	{
		$this->original_position['x'] = $x;
		$this->original_position['y'] = $y;
		if (!empty($distance))
		{
			$this->_moveOriginalPosition($angle, $distance);
		}
		// initialize also resulting position
		$this->position['x'] = $this->original_position['x'];
		$this->position['y'] = $this->original_position['y'];
	}
	private function _moveOriginalPosition($angle, $distance)
	{
		$this->original_position['x'] += sin($angle) * $distance;
		$this->original_position['y'] -= cos($angle) * $distance;
	}
	public function getPoint()
	{
		return $this;
	}
	public function getPoints()
	{
		return array($this);
	}
	public function setRotate($move_x, $move_y, $rotate, $center_x, $center_y, $mirror = false)
	{
		$this->position['x'] = $this->original_position['x'] + $move_x;
		$this->position['y'] = $this->original_position['y'] + $move_y;
		
		$distance_to_center = sqrt(pow($this->position['x'] - $center_x, 2) + pow($this->position['y'] - $center_y, 2));
		
		$angle = acos(($this->position['y'] - $center_y) / $distance_to_center);
		if ($this->position['x'] - $center_x < 0)
		{
			$angle = 2*pi() - $angle;
		}
		
		// echo '<p>'.$this->position['x'].', '.$this->position['y'].' => '.$angle;
		
		$new_angle = $angle - $rotate;
		// echo ' => '.$new_angle;
		
		$this->position['x'] = sin($new_angle) * $distance_to_center + $center_x;
		$this->position['y'] = cos($new_angle) * $distance_to_center + $center_y;
		
		if ($mirror)
		{
			$this->position['x'] = 2 * $center_x - $this->position['x'];
		}
		
		// echo ' '.$this->position['x'].', '.$this->position['y'].')';
	}
	public function x($drawable = false)
	{
		return $drawable
			? $this->position['x']
			: $this->original_position['x'];
	}
	public function y($drawable = false)
	{
		return $drawable
			? $this->position['y']
			: $this->original_position['y'];
	}
}