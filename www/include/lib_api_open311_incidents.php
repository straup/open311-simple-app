<?php

	loadlib("open311_services");
	loadlib("open311_statuses");
	loadlib("open311_incidents");
	loadlib("geo_utils");

	##############################################################################

	function api_open311_incidents_report(){

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

		$rsp = open311_incidents_add_incident($incident);

		if (! $rsp['ok']){
			api_output_error(999, $rsp['error']);
		}

		$out = array('id' => $rsp['incident']['id']);

		api_output_ok($out);
		exit();
	}

	##############################################################################

	function api_open311_incidents_getInfo(){

		$id = get_int64("incident_id");

		if (! $id){
			api_output_error(999, "Missing incident ID");
		}

		$incident = open311_incidents_get_by_id($id);

		if (! $incident){
			api_output_error(999, "Invalid incident ID");
		}

		$incident = _api_open311_incidents_prep_incident($incident);

		api_output_ok($incident);
		exit();
	}

	##############################################################################

	function api_open311_incidents_search(){

		# please write me...
	}

	##############################################################################

	function _api_open311_incidents_prep_incident($incident){

		$incident = open311_incidents_scrub_incident($incident);
		$incident['latitude'] = floatval($incident['latitude']);
		$incident['longitude'] = floatval($incident['longitude']);
		$incident['status_id'] = intval($incident['status_id']);
		$incident['service_id'] = intval($incident['service_id']);

		# incident IDs are BIGINTS...

		return $incident;
	}

	##############################################################################
?>
