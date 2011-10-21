<?php

	loadlib("open311_statuses");

	##############################################################################

	function api_open311_statuses_getList(){

		$args = array();

		if ($page = get_int32("page")){
			$args['page'] = $page;
		}

		if ($per_page = get_int32("per_page")){
			$args['per_page'] = $per_page;
		}

		$rsp = open311_statuses_get_statuses($args);

		# TO DO: add pagination variables

		return api_output_ok($rsp['rows']);
	}

	##############################################################################

?>
