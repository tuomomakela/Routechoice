<?php

function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}
function routeLength($point1, $point2)
{
	return sqrt(pow($point1[0] - $point2[0], 2) + pow($point1[1] - $point2[1], 2));
}
function calculateCornerPoint($point, $point_prev, $point_next)
{
	$distance = 5;
	$temp['a'] = routeLength($point, $point_prev);
	$temp['b'] = routeLength($point, $point_next);
	$temp['c'] = routeLength($point_prev, $point_next);
	
	$point_d = array(
		$temp['b'] / ($temp['a'] + $temp['b']) * $point_prev[0] + $temp['a'] / ($temp['a'] + $temp['b']) * $point_next[0],
		$temp['b'] / ($temp['a'] + $temp['b']) * $point_prev[1] + $temp['a'] / ($temp['a'] + $temp['b']) * $point_next[1]
	);
	$length_ad = routeLength($point, $point_d);
	
	return array(
		$point[0] + ($point[0] - $point_d[0]) * $distance / $length_ad,
		$point[1] + ($point[1] - $point_d[1]) * $distance / $length_ad
	);
}


$canvas = array(600,600);

$area1 = array(
	array(100,100),
	array(400,100),
	array(400,200),
	array(100,200)
);

$control1 = array(150,60);
$control2 = array(400,400);

$route[0] = array(
	$control1,
	calculateCornerPoint($area1[0],$area1[1],$area1[3]),
	calculateCornerPoint($area1[3],$area1[0],$area1[2]),
	$control2
);

$route[1] = array(
	$control1,
	calculateCornerPoint($area1[1],$area1[0],$area1[2]),
	calculateCornerPoint($area1[2],$area1[1],$area1[3]),
	$control2
);
$css::getSize();
$image = imagecreatetruecolor($canvas[0], $canvas[1]);

$color['background'] = imagecolorallocate($image,255,255,255);
$color['control'] = imagecolorallocate($image,102,0,153);
$color['area1'] = imagecolorallocate($image,0,0,0);
$color['routechoice'][0] = imagecolorallocate($image,64,255,64);
$color['routechoice'][1] = imagecolorallocate($image,255,64,64);

imagefill($image,0,0,$color['background']);

imagefilledpolygon(
	$image,
	flatten($area1),
	count($area1),
	$color['area1']
);

imagesetthickness($image, 3);

imageellipse(
	$image,
	$control1[0],
	$control1[1],
	60,
	60,
	$color['control']
);
imageellipse(
	$image,
	$control2[0],
	$control2[1],
	60,
	60,
	$color['control']
);

for ($j = 0; $j < 2; ++$j)
{
	for ($i = 1; $i < count($route[$j]); ++$i)
	{
		imageline(
			$image,
			$route[$j][$i-1][0],
			$route[$j][$i-1][1],
			$route[$j][$i][0],
			$route[$j][$i][1],
			$color['routechoice'][$j]
		);
	}
}

//header("Content-Type: image/png");
//imagepng($image);
//exit;


ob_start();
imagepng($image);
$image_data = ob_get_contents();
ob_end_clean();
echo '<img src="data:image/png;base64,'.base64_encode($image_data).'" />';

$lengths = array();
for ($j = 0; $j < 2; ++$j)
{
	$lengths[$j] = 0;
	for ($i = 1; $i < count($route[$j]); ++$i)
	{
		$lengths[$j] += routeLength($route[$j][$i], $route[$j][$i-1]);
	}
}

echo '<p>Route lengths: ';
echo '<p>Route 1: '.$lengths[0];
echo '<p>Route 2: '.$lengths[1];

exit;

//Save the image as 'simpletext.jpg'
imagejpeg($image, 'test.jpg');

imagedestroy($image);

echo '<img src="test.jpg" />';
