<?php

	loadlib("services");
	loadlib("statuses");
	loadlib("incidents");
	loadlib("geo_utils");

	function api_incidents_report(){

		$service_id = post_int32("service_id");

		if (! $service_id){
			api_output_error(999, "Missing service ID");
		}

		if (! services_is_valid_service($service_id)){
			api_output_error(999, "Invalid service ID");
		}

		$lat = post_float("latitude");

		if (! $lat){
			api_output_error(999, "Missing latitude");
		}

		if (! geo_utils_is_valid_latitude($lat)){
			api_output_error(999, "Invalid latitude");
		}

		$lon = post_float("longitude");

		if (! $lon){
			api_output_error(999, "Missing longitude");
		}

		if (! geo_utils_is_valid_longitude($lon)){
			api_output_error(999, "Invalid longitude");
		}

		$desc = trim(post_str("description"));

		if ($desc){
			$desc = filter_strict($desc);
		}

		$incident = array(
			'user_id' => $GLOBALS['cfg']['user']['id'],
			'service_id' => $service_id,
			'latitude' => $lat,
			'longitude' => $lon,
			'description' => $desc,
		);

		$rsp = incidents_add_incident($incident);

		if (! $rsp['ok']){
			api_output_error(999, $rsp['error']);
		}

		$out = array('id' = $rsp['incident']['id']);

		api_output_ok($out);
		exit();
	}

	##############################################################################

	function api_incidents_getInfo(){

		$id = get_int64("incident_id");

		if (! $id){
			api_output_error(999, "Missing incident ID");
		}

		$incident = incidents_get_by_id($id);

		if (! $incident){
			api_output_error(999, "Invalid incident ID");
		}

		$incident = incidents_scrub_incident($incident);

		api_output_ok($incident);
		exit();
	}

	##############################################################################

	function api_incidents_getStatuses(){

		$args = array();

		if ($page = get_int32("page")){
			$args['page'] = $page;
		}

		if ($per_page = get_int32("per_page")){
			$args['per_page'] = $per_page;
		}

		$rsp = statuses_get_statuses($args);

		# TO DO: add pagination variables

		return api_output_ok($rsp['rows']);
	}

	##############################################################################

	function api_incidents_search(){

		# please write me...
	}

	##############################################################################
?>
