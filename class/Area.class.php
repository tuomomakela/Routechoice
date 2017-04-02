<?php

class Area
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
	protected function _getInnerPointsArray($width, $drawable)
	{
		$points = $this->getPoints();
		$new_points = array();
		for ($i = 0; $i < count($points); ++$i)
		{
			$prev_point = $i == 0
				? end($points)
				: $points[$i - 1];
			$next_point = $i == count($points) - 1
				? $points[0]
				: $points[$i + 1];
			$cur_point = $points[$i];
			
			// laske pisteet, jotka ovat 1 päässä edellisen ja seuraavan suuntaan
			$length_1 = sqrt(pow($cur_point->x() - $prev_point->x(), 2) + pow($cur_point->y() - $prev_point->y(), 2));
			$prev_1_x = $cur_point->x($drawable) + ($prev_point->x($drawable) - $cur_point->x($drawable)) / $length_1;
			$prev_1_y = $cur_point->y($drawable) + ($prev_point->y($drawable) - $cur_point->y($drawable)) / $length_1;
			
			$length_2 = sqrt(pow($cur_point->x() - $next_point->x(), 2) + pow($cur_point->y() - $next_point->y(), 2));
			$next_1_x = $cur_point->x($drawable) + ($next_point->x($drawable) - $cur_point->x($drawable)) / $length_2;
			$next_1_y = $cur_point->y($drawable) + ($next_point->y($drawable) - $cur_point->y($drawable)) / $length_2;
			
			$middle_point_x = ($prev_1_x + $next_1_x) / 2;
			$middle_point_y = ($prev_1_y + $next_1_y) / 2;
			
			// length between middle point and cur point
			$length_3 = sqrt(pow($cur_point->x($drawable) - $middle_point_x, 2) + pow($cur_point->y($drawable) - $middle_point_y, 2));
			// angle of half corner
			$angle = acos($length_3 / 1);
			//distance from corner
			$distance_1 = $width / sin($angle);
			
			$ratio = $distance_1 / $length_3;
			
			$new_point_x = $cur_point->x($drawable) + ($middle_point_x - $cur_point->x($drawable)) * $ratio;
			$new_point_y = $cur_point->y($drawable) + ($middle_point_y - $cur_point->y($drawable)) * $ratio;
			
			$new_points[] = $new_point_x;
			$new_points[] = $new_point_y;
			
			// echo '<p>'.$cur_point->x($drawable).', '.$cur_point->y($drawable).': '.$new_point_x.', '.$new_point_y;
		}
		return $new_points;
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
