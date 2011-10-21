<?php

	loadlib("open311_services");

	##############################################################################

	function api_open311_services_getList(){

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

		$rsp = open311_services_get_services($args);

		$out = array(
			'total' => $rsp['pagination']['total_count'],
			'page' => $rsp['pagination']['page'],
			'per_page' => $rsp['pagination']['per_page'],
			'pages' => $rsp['pagination']['page_count'],
			'services' => $rsp['rows'],
		);

		return api_output_ok($out);
	}

	##############################################################################

	function api_open311_services_getInfo(){

		$id = get_int32("service_id");

		if (! $id){
			api_output_error(999, "Missing service ID");
		}

		$service = open311_services_get_by_id($id);

		if (! $service['id']){
			api_output_error(999, "Invalid service ID");
		}

		$out = array(
			'service' => $service,
		);

		api_output_ok($out);
	}

	##############################################################################
?>
