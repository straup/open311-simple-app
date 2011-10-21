<?php

	loadlib("open311_where");

	##############################################################################

	function api_open311_where_getList(){

		$map = open311_where_map();
		$count = count($map);

		# TO DO: real pagination...

		$out = array(
			'page' => 1,
			'pages' => 1,
			'per_page' => $count,
			'total' => $count,
			'terms' => $map,
		);

		api_output_ok($out);
	}

	##############################################################################
?>
