<?php

	require_once(dirname(__FILE__).'/inc/map_funcs.inc.php');
	
	foreach (glob("class/*.php") as $filename)
	{
		require_once($filename);
	}
	
	require_once('head.php');
	
	echo '<h2 class="text-center">Reitinvalintatesti</h2>';
	
?>
	<script>
		$(function() {
			var start = new Date();
			var stop;
			var time;
			$(".select-route").click(function() {
				stop = new Date();
				time = (stop - start) / 1000;
				console.log(time);
				$(".route-text").append("<div>Aikaa kului <strong>" + time.toFixed(2) + " sekuntia</strong>.");
				$(".map").hide();
				$(".map_with_routes").show();
				if ($(this).attr("id") === 'route1') {
					$("#route1-text").show();
				} else if ($(this).attr("id") === 'route2') {
					$("#route2-text").show();
				}
			});
		});
	</script>

<?php

	// create image
	$map = New Course1();
	
	// get image
	$image = $map->getMap();
	
	ob_start();
	imagepng($image);
	$map_data = ob_get_contents();
	ob_end_clean();
	
	$image_with_routes = $map->getRoutes();
	
	ob_start();
	imagepng($image_with_routes);
	$map_with_routes_data = ob_get_contents();
	ob_end_clean();
	
	$route1_length = $map->getRouteLength('left');
	$route2_length = $map->getRouteLength('right');
	$route_length_difference = max(array($route1_length, $route2_length)) / min(array($route1_length, $route2_length)) * 100 - 100;
	
	if (floor($route_length_difference) == 0)
	{
		$route1_text = '<h2>Reitit olivat yhtä pitkiä</h2>';
		$route2_text = '<h2>Reitit olivat yhtä pitkiä</h2>';
	}
	else if ($route1_length < $route2_length)
	{
		
		$route1_text = '<h2 class="answer-right">Oikein!</h2><div>Oikea reitti olisi ollut <strong>'.number_format($route_length_difference, 0).' %</strong> pidempi.</div>';
		$route2_text = '<h2 class="answer-wrong">Väärin!</h2><div>Vasen reitti olisi ollut <strong>'.number_format($route_length_difference, 0).' %</strong> lyhyempi.</div>';
	}
	else
	{
		$route1_text = '<h2 class="answer-wrong">Väärin!</h2><div>Oikea reitti olisi ollut <strong>'.number_format($route_length_difference, 0).' %</strong> lyhyempi.</div>';
		$route2_text = '<h2 class="answer-right">Oikein!</h2><div>Vasen reitti olisi ollut <strong>'.number_format($route_length_difference, 0).' %</strong> pidempi.</div>';
	}
	
	echo '
		<div class="row">
			<div class="map col-xs-12">
				<img src="data:image/png;base64,'.base64_encode($map_data).'" class="img-responsive center-block" />
			</div>
			<div class="map_with_routes col-xs-12" style="display:none;">
				<img src="data:image/png;base64,'.base64_encode($map_with_routes_data).'" class="img-responsive center-block" />
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 map text-center margin-top-20">
				<button class="select-route btn btn-lg btn-primary" id="route1">Vasen</button>
				<button class="select-route btn btn-lg btn-primary" id="route2">Oikea</button>
			</div>
			<div class="col-xs-12 map_with_routes text-center" style="display:none;">
				<div>
					<span id="route1-text" class="route-text" style="display:none;">'.$route1_text.'</span>
					<span id="route2-text" class="route-text" style="display:none;">'.$route2_text.'</span>
				</div>
				<div class="margin-top-20">
					<button class="btn btn-lg btn-success" onclick="location.reload();">Uudestaan!</button>
				</div>
			</div>
		</div>
	';
	
	require_once('foot.php');