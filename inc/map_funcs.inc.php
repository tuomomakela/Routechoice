<?php

function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

function routeLength($point1, $point2)
{
	return sqrt(pow($point1->x() - $point2->x(), 2) + pow($point1->y() - $point2->y(), 2));
}

function angle($x1, $y1, $x2, $y2)
{
	// eka on alhaalla
	if ($y1 == $y2)
	{
		// eka on vasemmalla ensin
		$angle = ($x1 < $x2)
			? 1 / 2 * pi()
			: 3 / 2 * pi();
	}
	else if ($y1 > $y2)
	{
		// eka on vasemmalla ensin
		$angle = ($x1 < $x2)
			? atan(($x2 - $x1) / ($y1 - $y2))
			: 2*pi() - atan(($x1 - $x2) / ($y1 - $y2));
	}
	else
	{
		// eka on vasemmalla ensin
		$angle = ($x1 < $x2)
			? pi() - atan(($x2 - $x1) / ($y2 - $y1))
			: pi() + atan(($x1 - $x2) / ($y2 - $y1));
	}
	return $angle;
}
function naturalAngle($angle)
{
	while ($angle > 2 * pi())
	{
		$angle -= 2 * pi();
	}
	while ($angle < 0)
	{
		$angle += 2 * pi();
	}
	return $angle;
}

function calculateCornerPoint($point, $point_prev, $point_next)
{
	$distance = 5;
	$temp['a'] = routeLength($point, $point_prev);
	$temp['b'] = routeLength($point, $point_next);
	$temp['c'] = routeLength($point_prev, $point_next);
	
	$point_d = new Point(
		$temp['b'] / ($temp['a'] + $temp['b']) * $point_prev[0] + $temp['a'] / ($temp['a'] + $temp['b']) * $point_next[0],
		$temp['b'] / ($temp['a'] + $temp['b']) * $point_prev[1] + $temp['a'] / ($temp['a'] + $temp['b']) * $point_next[1]
	);
	$length_ad = routeLength($point, $point_d);
	
	return array(
		$point->x() + ($point->x() - $point_d->x()) * $distance / $length_ad,
		$point->y() + ($point->y() - $point_d->y()) * $distance / $length_ad
	);
}