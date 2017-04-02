<?php

require_once(dirname(__FILE__).'/Building.class.php');
require_once(dirname(__FILE__).'/Control.class.php');
require_once(dirname(__FILE__).'/Map.class.php');
require_once(dirname(__FILE__).'/Route.class.php');

class Course1 extends Map
{
	protected function _setMapSize()
	{
		$this->map_size = array(
			'width' => 520,
			'height' => 520
		);
	}
	protected function _setElements()
	{
		// map
		$rand_map_1 = rand(280,320);
		$rand_map_2 = rand(75,125);
		$rand_map_3 = rand(0,3) > 0 ? 1 : 0;
		$rand_map_4 = rand(0,3) > 0 ? 1 : 0;
		$rand_map_5 = rand(0,50);
		$rand_map_6 = rand(0,50);
		$rand_map_7 = rand(0,3) > 0 ? 1 : 0;
		$rand_map_8 = rand(50,80);
		$rand_map_9 = rand(75,125);
		$rand_map_10 = rand(0,280);
		$rand_map_11 = rand(0,280);
		$rand_map_12 = rand(0,3) > 0 ? 1 : 0;
		
		// view
		$order = rand(0,1);
		$mirror = rand(0,1);
		
		$this->mirror = $mirror == 1;
		
		// set elements
		$x1 = 100;
		$x2 = $x1 + $rand_map_1;
		$y1 = 100;
		$y2 = $y1 + $rand_map_2;
		
		$this->elements[] = new Building(
			array(
				array($x1,$y1),
				array($x2,$y1),
				array($x2,$y2),
				array($x1,$y2)
			)
		);
		if ($rand_map_3 == 1)
		{
			$x3 = $x1 - 20 - $rand_map_5 / 2;
			$x4 = $x3 - (100 + 6 * $rand_map_5);
			$y4 = $y2 + $rand_map_10;
			
			$this->elements[] = new Building(
				array(
					array($x3,$y1),
					array($x4,$y1),
					array($x4,$y4),
					array($x3,$y4)
				)
			);
		}
		if ($rand_map_4 == 1)
		{
			$x5 = $x2 + 20 + $rand_map_6 / 2;
			$x6 = $x5 + (100 + 6 * $rand_map_6);
			$y6 = $y2 + $rand_map_11;
			
			$this->elements[] = new Building(
				array(
					array($x5,$y1),
					array($x6,$y1),
					array($x6,$y6),
					array($x5,$y6)
				)
			);
		}
		if ($rand_map_7 == 1)
		{
			$y8 = $y1 - $rand_map_8;
			$y9 = $y8 - $rand_map_9;
			
			$this->elements[] = new Building(
				array(
					array($x1,$y8),
					array($x2,$y8),
					array($x2,$y9),
					array($x1,$y9)
				)
			);
		}
		if ($rand_map_12 == 1)
		{
			$x10 = $x1 - 1000;
			$x11 = $x2 + 1000;
			$y10 = $y2 + 300;
			$y11 = $y10 + 1000;
			$this->elements[] = new Lake(
				array(
					array($x10,$y10),
					array($x11,$y10),
					array($x11,$y11),
					array($x10,$y11)
				)
			);
		}
		
		// set controls
		// controls
		$rand_c_1 = rand(40,100);
		$rand_c_2 = rand(5,30);
		$rand_c_3 = rand(0,40);
		$rand_c_4 = rand(125,250);
		
		$xc1 = $x1 + $rand_c_1;
		$yc1 = $y1 - $rand_c_2;
		
		$this->controls[$order] = new Control($xc1, $yc1);
		
		$xc2 = $x2 - $rand_c_3;
		$yc2 = $y2 + $rand_c_4;
		
		$this->controls[1 - $order] = new Control($xc2, $yc2);
		
		$this->elements[] = new ManMadeFeature($xc1,$yc1);
		$this->elements[] = new SmallTree($xc2,$yc2);
		
		// set routes
		$routes = array();
		$directions = array();
		
		$routes[0] = array(
			$this->controls[$order]->getPoint(),
			$this->elements[0]->getPoint(1),
			$this->elements[0]->getPoint(2),
			$this->controls[1 - $order]->getPoint()
		);
		$directions[0] = array(
			0, -1, -1, 0
		);
		$routes[1] = array(
			$this->controls[$order]->getPoint(),
			$this->elements[0]->getPoint(0),
			$this->elements[0]->getPoint(3),
			$this->controls[1 - $order]->getPoint()
		);
		$directions[1] = array(
			0, 1, 1, 0
		);
		$reverse = $order == 1;
		$this->routes[0] = new Route($routes[0], $directions[0], $reverse);
		$this->routes[1] = new Route($routes[1], $directions[1], $reverse);
	}
}
	