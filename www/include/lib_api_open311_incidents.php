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

		$rsp = open311_incidents_get_notes($incident);

		# throw an error, if not?

		if ($rsp['ok']){
			$incident['notes'] = $rsp['rows'];
		}

		$incident = _api_open311_incidents_prep_incident($incident);

		api_output_ok($incident);
		exit();
	}

	##############################################################################

	function api_open311_incidents_addNote(){

		$iid = post_int64("incident_id");

		if (! $iid){
			api_error_output(999, "Missing incident ID");
		}

		$incident = incidents_get_by_id($iid);

		if (! $incident['id']){
			api_error_output(999, "Invalid incident ID");
		}

		$note = post_str("note");

		if (! $note){
			api_error_output(999, "Missing note");
		}

		$note = trim(filter_strict($note));

		if (! $note){
			api_error_output(999, "Invalid note");
		}

		$public = (post_str("public")) ? 1 : 0;

		$note = array(
			'incident_id' => $incident['id'],
			'public' => $public,
			'note' => $note,
			'user_id' => $GLOBALS['cfg']['user']['id'],
		);

		$rsp = incidents_open311_add_note($incident, $note);

		if (! $rsp['ok']){
			api_error_output(999, $rsp['error']);
		}

		$out = array(
			'id' => $rsp['note']['id'],
			'incident_id' => $incident['id'],
		);

		api_output_ok($out);		
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

		api_utils_ensure_pagination_args($args);

		$rsp = open311_search($args);

		if (! $rsp['ok']){
			api_output_error(999, $rsp['error']);
		}

		$rows = array();

		foreach ($rsp['rows'] as $row){
			$rows[] = _api_open311_incidents_prep_incident($row);
		}

		$out = array(
			'incidents' => $rows,
		);

		api_utils_ensure_pagination_results($out, $rsp['pagination']);

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
