<?php

require_once(dirname(__FILE__).'/Building.class.php');
require_once(dirname(__FILE__).'/Control.class.php');
require_once(dirname(__FILE__).'/Map.class.php');
require_once(dirname(__FILE__).'/Route.class.php');

class Course2 extends Map
{
	/*protected function _rotateMap()
	{
		// nothing
	}*/
	protected function _setMapSize()
	{
		$this->map_size = array(
			'width' => 720,
			'height' => 520
		);
	}
	protected function _setElements()
	{
		// view
		$order = rand(0,1);
		$mirror = rand(0,1);
		
		$this->mirror = $mirror == 1;
		
		
		
		// map
		$rand_map_1 = rand(80,120);
		$rand_map_2 = rand(220,300);
		$rand_map_3 = rand(250,320);
		$rand_map_4 = rand(40,60);
		$rand_map_5 = $rand_map_3 + rand(50,80);
		$rand_map_6 = rand(40,60);
		
		
		// set elements
		$x1 = 200;
		$x2 = $x1 + $rand_map_1;
		$y1 = 200;
		$y2 = $y1 + $rand_map_2;
		
		$this->elements[] = new Building(
			array(
				array($x1,$y1),
				array($x2,$y1),
				array($x2,$y2),
				array($x1,$y2)
			)
		);
		
		$x3 = $x2;
		$y3 = $y1;
		$x4 = $x2 + $rand_map_3;
		$y4 = $y1;
		
		$this->elements[] = new ImpassableWall(
			array(
				array($x3,$y3),
				array($x4,$y4)
			)
		);
		
		$x5 = $x2 + 40;
		$y5 = $y2 - $rand_map_4;
		$x6 = $x5 + $rand_map_5;
		$y6 = $y5 + $rand_map_6;
		
		$this->elements[] = new Building(
			array(
				array($x5,$y5),
				array($x6,$y5),
				array($x6,$y6),
				array($x5,$y6)
			)
		);
		
		$x7 = $x4 + 30;
		$y7 = $y1;
		$x8 = $x6;
		$y8 = $y1;
		$x9 = $x6;
		$y9 = $y5;
		
		$this->elements[] = new ImpassableWall(
			array(
				array($x7,$y7),
				array($x8,$y8),
				array($x9,$y9)
			)
		);
		
		// set controls
		// controls
		$rand_c_3 = rand(20,30);
		$rand_c_4 = rand(20,30);
		
		$xc1 = $x2 + $rand_c_3;
		$yc1 = $y5 + $rand_c_4;
		
		$this->controls[$order] = new Control($xc1, $yc1);

		$this->elements[] = new ManMadeFeature($xc1,$yc1);
		
		do
		{
			$rand_c_1 = rand(20,250);
			$rand_c_2 = rand(5,30);
		
			$xc2 = $x1 + $rand_c_1;
			$yc2 = $y1 - $rand_c_2;
			
			$this->controls[1 - $order] = new Control($xc2, $yc2);
			
			// set routes
			$routes = array();
			$directions = array();
			
			$routes[0] = array(
				$this->controls[$order]->getPoint(),
				$this->elements[0]->getPoint(2),
				$this->elements[0]->getPoint(3),
				$this->elements[0]->getPoint(0),
				$this->controls[1 - $order]->getPoint()
			);
			$directions[0] = array(
				0, -1, -1, -1, 0
			);
			$routes[1] = array(
				$this->controls[$order]->getPoint(),
				$this->elements[2]->getPoint(0),
				$this->elements[1]->getPoint(1),
				$this->controls[1 - $order]->getPoint()
			);
			$directions[1] = array(
				0, -1, 1, 0
			);
			$reverse = $order == 1;
			$this->routes[0] = new Route($routes[0], $directions[0], $reverse);
			$this->routes[1] = new Route($routes[1], $directions[1], $reverse);
			
			$ratio = $this->routes[0]->getLength() / $this->routes[1]->getLength();
			
			if ($ratio < 1)
			{
				$ratio = 1 / $ratio;
			}
		} while ($ratio > 1.2);
		
		$this->elements[] = new SmallTree($xc2,$yc2);
	}
}
	