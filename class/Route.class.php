<?php

function negative_value($value)
{
	return -$value;
}

class Route
{
	private $points = array();
	// -1 pass point from the left, 1 pass point from the right, 0 go through the point
	private $directions = array();
	private $route_length = 0;
	
	private $passing_distance = 5;
	
	public function __construct($points, $directions, $reverse = false)
	{
		$this->points = $points;
		$this->directions = $directions;
		if ($reverse)
		{
			$this->points = array_reverse($this->points);
			$this->directions = array_reverse($this->directions);
			$this->directions = array_map("negative_value", $this->directions);
		}
		$this->_recalculatePoints();
		$this->_calculateLength();
	}
	private function _calculateLength()
	{
		$this->route_length = 0;
		for ($i = 1; $i < count($this->points); ++$i)
		{
			$this->route_length += routeLength($this->points[$i-1], $this->points[$i]);
		}
	}
	private function _recalculatePoints()
	{
		$new_points = array();
		$points_to_leave_from = array();
		
		for ($i = 0; $i < count($this->points); ++$i)
		{
			// if directions == 0 or first or last point
			if ($this->directions[$i] == 0 || $i == 0 || $i == count($this->points) - 1)
			{
				$new_points[] = new Point($this->points[$i]->x(), $this->points[$i]->y());
				$points_to_leave_from[$i] = end($new_points);
			}
			else
			{
				$move_x = $this->points[$i]->x() - $points_to_leave_from[$i - 1]->x();
				$move_y = $this->points[$i]->y() - $points_to_leave_from[$i - 1]->y();
				$move_length = sqrt(pow($move_x, 2) + pow($move_y, 2));
				
				// angle between 0-1 and 0-new
				$temp_angle1 = asin($this->passing_distance / $move_length);
				// absolute direction of 0-1
				$temp_angle2 = angle($points_to_leave_from[$i - 1]->x(), $points_to_leave_from[$i -1]->y(), $this->points[$i]->x(), $this->points[$i]->y());
				
				$first_angle = $temp_angle2 + $this->directions[$i] * $temp_angle1 + $this->directions[$i] * pi() / 2;
				$first_angle = naturalAngle($first_angle);
				//$first_angle = $first_angle % (2 * pi());
				// echo '<p>Point 1:'.$this->points[$i]->x().' & '. $this->points[$i]->y().' ('.$this->directions[$i].') : '.$temp_angle1.' + '.$temp_angle2.' => '.$first_angle;
				$new_points[] = new Point($this->points[$i]->x(), $this->points[$i]->y(), $first_angle, $this->passing_distance);
				
				// calculate last point of corner
				if ($this->directions[$i + 1] == 0)
				{
					$move_x = $this->points[$i + 1]->x() - $this->points[$i]->x();
					$move_y = $this->points[$i + 1]->y() - $this->points[$i]->y();
					$move_length = sqrt(pow($move_x, 2) + pow($move_y, 2));
					
					// angle between 1-2 and 1-new
					$temp_angle1 = acos($this->passing_distance / $move_length);
					// absolute direction of 1-2
					$temp_angle2 = angle($this->points[$i]->x(), $this->points[$i]->y(), $this->points[$i + 1]->x(), $this->points[$i + 1]->y());
					$last_angle = $temp_angle2 + $this->directions[$i] * $temp_angle1;
					
					// echo '<p>Point 2:'.$this->points[$i]->x().' & '. $this->points[$i]->y().' ('.$this->directions[$i].') : '.$temp_angle1.' + '.$temp_angle2.' => '.$last_angle;
					//$last_angle %= (2 * pi());
				}
				else if ($this->directions[$i] == $this->directions[$i + 1])
				{
					$temp_angle1 = angle($this->points[$i]->x(), $this->points[$i]->y(), $this->points[$i + 1]->x(), $this->points[$i + 1]->y());
					//echo '<p>'.$temp_angle1;
					$last_angle = $temp_angle1 + $this->directions[$i] * pi() / 2;
				}
				else
				{
					$move_x = $this->points[$i + 1]->x() - $this->points[$i]->x();
					$move_y = $this->points[$i + 1]->y() - $this->points[$i]->y();
					$move_length = sqrt(pow($move_x, 2) + pow($move_y, 2));
					
					$temp_angle1 = acos(2 * $this->passing_distance / $move_length);
					
					$temp_angle2 = angle($this->points[$i]->x(), $this->points[$i]->y(), $this->points[$i + 1]->x(), $this->points[$i + 1]->y());
					$last_angle = $temp_angle2 + $this->directions[$i] * $temp_angle1;
				}
				$last_angle = naturalAngle($last_angle);
				
				if ($this->directions[$i] == -1)
				{
					if ($first_angle > $last_angle)
					{
						$last_angle += 2 * pi();
					}
					if ($last_angle - $first_angle > pi() / 2 / 8)
					{
						$n = floor(($last_angle - $first_angle) / (pi() / 2 / 8));
						$angle_add = ($last_angle - $first_angle) / $n;
						for ($j = 1; $j < $n; ++$j)
						{
							$new_angle = $first_angle + $j * $angle_add;
							$new_points[] = new Point($this->points[$i]->x(), $this->points[$i]->y(), $new_angle, $this->passing_distance);
						}
					}
				}
				else if ($this->directions[$i] == 1)
				{
					if ($first_angle < $last_angle)
					{
						$first_angle += 2 * pi();
					}
					if ($first_angle - $last_angle > pi() / 2 / 8)
					{
						$n = floor(($first_angle - $last_angle) / (pi() / 2 / 8));
						$angle_add = ($first_angle - $last_angle) / $n;
						for ($j = 1; $j < $n; ++$j)
						{
							$new_angle = $first_angle - $j * $angle_add;
							$new_points[] = new Point($this->points[$i]->x(), $this->points[$i]->y(), $new_angle, $this->passing_distance);
						}
					}
				}
				
				$new_points[] = new Point($this->points[$i]->x(), $this->points[$i]->y(), $last_angle, $this->passing_distance);
				$points_to_leave_from[$i] = end($new_points);
			}
		}
		$this->points = $new_points;
	}
	public function draw($image, $color)
	{
		imagesetthickness($image, 3);
		$drawable = true;
		for ($i = 1; $i < count($this->points); ++$i)
		{
			// line between controls
			imageline(
				$image,
				$this->points[$i-1]->x($drawable),
				$this->points[$i-1]->y($drawable),
				$this->points[$i]->x($drawable),
				$this->points[$i]->y($drawable),
				Color::getColor($image, $color)
			);
		}
	}
	public function getLength()
	{
		return $this->route_length;
	}
	public function getPoint($i)
	{
		return isset($this->points[$i])
			? $this->points[$i]
			: null;
	}
	public function getPoints()
	{
		return $this->points;
	}
}