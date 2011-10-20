<?php

	loadlib("services");

	##############################################################################

	function api_services_getList(){

		$args = array();

		if ($page = get_int32("page")){
			$args['page'] = $page;
		}

		if ($per_page = get_int32("per_page")){
			$args['per_page'] = $per_page;
		}

		$rsp = services_get_services($args);

		# TO DO: add pagination variables

		return api_output_ok($rsp['rows']);
	}

	##############################################################################

	function api_services_getInfo(){

		# please write me...
	}

	##############################################################################
?>
