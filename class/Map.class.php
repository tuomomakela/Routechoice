<?php

class Map
{
	protected $elements = array();
	protected $controls = array();
	protected $map_size = array(
		'width' => 500,
		'height' => 500
	);
	protected $routes = array();
	
	private $map_image = null;
	private $routes_image = null;
	
	public function __construct()
	{
		$this->_setElements();
		
		$this->_setMapSize();
		
		$this->_rotateMap();
		
		$this->map_image = imagecreatetruecolor($this->map_size['width'],$this->map_size['height']);
		
		$this->_drawBackground();
		
		$this->_drawElements();
		$this->_drawControls();
		
		$this->routes_image = imagecreatetruecolor($this->map_size['width'],$this->map_size['height']);
		imagecopy($this->routes_image, $this->map_image, 0, 0, 0, 0, $this->map_size['width'],$this->map_size['height']);
		
		$this->_drawRoutes();
		
	}
	protected function _drawBackground()
	{
		imagefill(
			$this->map_image,
			0,
			0,
			Color::getColor($this->map_image, 'pavement')
		);
	}
	protected function _drawControls()
	{
		$drawable = true;
		$this->controls[0]->draw($this->map_image);
		$this->controls[1]->draw($this->map_image);
		
		//echo '<p>'.$this->controls[1]->y($drawable).' - '.$this->controls[0]->y($drawable);
		if (abs($this->controls[1]->x($drawable) - $this->controls[0]->x($drawable)) >= 0.01)
		{
			$ratio = ($this->controls[1]->x($drawable) - $this->controls[0]->x($drawable)) / ($this->controls[1]->y($drawable) - $this->controls[0]->y($drawable));
			// ratio = x / y
			// length x, length y => 60^2 = (1 + 1/ratio)^2 * x^2 => 60^2 / (1+1/ratio)
			$x_offset = sqrt(pow(30, 2) / (1 + pow(1 / $ratio, 2)));
			$y_offset = sqrt(pow(30, 2) / (1 + pow($ratio, 2)));
			//echo '<p>Offset: '.$x_offset.', '.$y_offset;
		}
		else
		{
			$x_offset = 0;
			$y_offset = 30;
		}
		if ($this->controls[0]->x($drawable) > $this->controls[1]->x($drawable))
		{
			$x_offset = -$x_offset;
		}
		if ($this->controls[0]->y($drawable) > $this->controls[1]->y($drawable))
		{
			$y_offset = -$y_offset;
		}
		imagesetthickness($this->map_image, 2);
		// line between controls
		imageline(
			$this->map_image,
			$this->controls[0]->x($drawable) + $x_offset,
			$this->controls[0]->y($drawable) + $y_offset,
			$this->controls[1]->x($drawable) - $x_offset,
			$this->controls[1]->y($drawable) - $y_offset,
			Color::getColor($this->map_image, 'controls')
		);
	}
	protected function _drawElements()
	{
		foreach ($this->elements as $e)
		{
			$e->draw($this->map_image);
		}
	}
	protected function _drawRoutes()
	{
		$route_lengths = array();
		$routes = $this->routes;
		foreach ($routes as $r_id => $route)
		{
			$route_lengths[$r_id] = $route->getLength();
		}
		array_multisort($route_lengths, $routes);
		foreach ($routes as $r_id => $route)
		{
			$color = 'route'.$r_id;
			$route->draw($this->routes_image, $color);
		}
		
	}
	protected function _getAllPoints()
	{
		$points = array();
		foreach ($this->elements AS $e)
		{
			foreach ($e->getPoints() AS $p)
			{
				$points[] = $p;
			}
		}
		foreach ($this->controls AS $e)
		{
			foreach ($e->getPoints() AS $p)
			{
				$points[] = $p;
			}
		}
		foreach ($this->routes AS $r)
		{
			foreach ($r->getPoints() AS $p)
			{
				$points[] = $p;
			}
		}
		return $points;
	}
	protected function _getMirrorValue()
	{
		return $this->mirror;
	}
	protected function _rotateMap()
	{
		$move_x = null;
		$move_y = null;
		$rotate = 0;
		$mirror = $this->_getMirrorValue();
		
		$orig_middle_x = ($this->controls[0]->x() + $this->controls[1]->x()) / 2;
		$orig_middle_y = ($this->controls[0]->y() + $this->controls[1]->y()) / 2;
		
		$new_middle_x = $this->map_size['width'] / 2;
		$new_middle_y = $this->map_size['height'] / 2;
		
		$move_x = $new_middle_x - $orig_middle_x;
		$move_y = $new_middle_y - $orig_middle_y;
		
		$rotate = 2 * pi() - angle($this->controls[0]->x(), $this->controls[0]->y(), $this->controls[1]->x(), $this->controls[1]->y());
		
		foreach ($this->_getAllPoints() AS $p)
		{
			$p->setRotate($move_x, $move_y, $rotate, $new_middle_x, $new_middle_y, $mirror);
		}
	}
	protected function _setElements()
	{
		// overriden by child class
	}
	protected function _setMapSize()
	{
		// overriden by child class
	}
	public function getMap()
	{
		return $this->map_image;
	}
	public function getRoutes()
	{
		return $this->routes_image;
	}
	public function getRouteLength($type)
	{
		$drawable = true;
		$order_is_correct = $this->routes[0]->getPoint(1)->x($drawable) < $this->routes[1]->getPoint(1)->x($drawable);
		if ($type === 'left')
		{
			return $this->routes[$order_is_correct ? 0 : 1]->getLength();
		}
		else if ($type === 'right')
		{
			return $this->routes[$order_is_correct ? 1 : 0]->getLength();
		}
		return null;
	}
}