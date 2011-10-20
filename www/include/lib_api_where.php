<?php

	loadlib("incidents_search");

	##############################################################################

	function api_where_getList(){

		$map = incidents_search_where_map();
		api_output_ok($map);
	}

	##############################################################################
?>
