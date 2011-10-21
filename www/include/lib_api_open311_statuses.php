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

		if (! $args['per_page']){
			$args['per_page'] = $GLOBALS['cfg']['api_per_page_default'];
		}

		else if ($args['per_page'] > $GLOBALS['cfg']['api_per_page_maximum']){
			$args['per_page'] = $GLOBALS['cfg']['api_per_page_maximum'];
		}

		$rsp = open311_statuses_get_statuses($args);

		$out = array(
			'total' => $rsp['pagination']['total_count'],
			'page' => $rsp['pagination']['page'],
			'per_page' => $rsp['pagination']['per_page'],
			'pages' => $rsp['pagination']['page_count'],
			'statuses' => $rsp['rows'],
		);

		return api_output_ok($out);
	}

	##############################################################################

	# open311.statuses.getInfo ... necessary?

?>
