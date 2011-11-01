<?php

	loadlib("open311_services");
	loadlib("open311_statuses");
	loadlib("open311_incidents");
	loadlib("open311_search");
	loadlib("open311_where");
	loadlib("open311_when");
	loadlib("geo_utils");

	##############################################################################

	function api_open311_incidents_report(){

		$service_id = post_int32("service_id");

		if (! $service_id){
			api_output_error(999, "Missing service ID");
		}

		if (! open311_services_is_valid_service($service_id)){
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

		foreach (array('incident_id') as $k){

			$ids = array();

			if ($v = get_str($k)){

				foreach (explode(",", $v) as $id){

					if (! sanitize_int64($id)){
						api_output_error(999, "Invalid ID ('{$k}')");
					}

					$ids[] = $id;
				}

				$args[$k] = $id;
			}
		}

		foreach (array('service_id', 'status_id') as $k){

			if ($v = get_str($k)){

				foreach (explode(",", $v) as $id){

					if (! sanitize_int64($id)){
						api_output_error(999, "Invalid ID ('{$k}')");
					}

					$ids[] = $id;
				}

				$args[$k] = $id;
			}
		}

		foreach (array('created', 'modified') as $k){

			if ($v = get_str($k)){

				$v = filter_strict(trim($v));

				if (! $v){
					api_output_error(999, "Invalid '{$k}' date");
				}

				foreach (explode(";", $v) as $dt){

					if (! open311_when_is_valid_date($dt)){
						api_output_error(999, "Invalid '{$k}' date");
					}
				}

				$args[$k] = $v;
			}
		}

		foreach (array('where') as $k){

			if ($v = get_str($k)){

				$v = filter_strict(trim($v));

				if (! $v){
					api_output_error(999, "Invalid where parameter ('{$k}')");
				}

				if (! open311_where_is_valid_prefix($v)){
					api_output_error(999, "Invalid where parameter ('{$k}') 2");
				}

				$args[$k] = $v;
			}
		}

		if (! count($args)){
			api_output_error(999, "parameterless searches are not allowed");
		}

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

		$rsp = open311_search($args);

		if (! $rsp['ok']){
			api_output_error(999, $rsp['error']);
		}

		$rows = array();

		foreach ($rsp['rows'] as $row){
			$rows[] = _api_open311_incidents_prep_incident($row);
		}

		$out = array(
			'total' => $rsp['pagination']['total_count'],
			'page' => $rsp['pagination']['page'],
			'per_page' => $rsp['pagination']['per_page'],
			'pages' => $rsp['pagination']['page_count'],
			'incidents' => $rows,
		);

		return api_output_ok($out);
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
