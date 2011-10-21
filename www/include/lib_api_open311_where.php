<?php

	loadlib("open311_where");

	##############################################################################

	function api_open311_where_getList(){

		$map = open311_incidents_search_where_map();
		api_output_ok($map);
	}

	##############################################################################
?>
