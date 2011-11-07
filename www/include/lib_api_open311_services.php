<?php

	loadlib("open311_services");

	##############################################################################

	function api_open311_services_getList(){

		$args = array();

		api_utils_ensure_pagination_args($args);

		$rsp = open311_services_get_services($args);

		$out = array(
			'services' => $rsp['rows'],
		);

		api_utils_ensure_pagination_results($out, $rsp['pagination']);
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
