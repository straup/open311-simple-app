<?php

	##############################################################################

	function open311_incidents_scrub_incident($incident){
		unset($incident['user_id']);
		return $incident;
	}

	##############################################################################

	function open311_incidents_get_by_id($incident_id){

		$enc_id = AddSlashes($incident_id);

		$sql = "SELECT * FROM Incidents WHERE id='{$enc_id}'";
		return db_single(db_fetch($sql));
	}

	##############################################################################

	function open311_incidents_add_incident($incident){

		$fmt = 'Y-m-d H:i:s';
		$now = time();

		$dt = gmdate($fmt, $now);

		if (! isset($incident['id'])){
			$incident['id'] = dbtickets_create();
		}

		if (! isset($incident['created'])){
			$incident['created'] = $dt;
		}

		$incident['last_modified'] = $dt;
		$incident['status_id'] = 1;	# FIX ME: read from database?

		$insert = array();

		foreach ($incident as $k => $v){
			$insert[$k] = AddSlashes($v);
		}

		$rsp = db_insert('Incidents', $insert);

		if ($rsp['ok']){
			$rsp['incident'] = $incident;
		}

		return $rsp;
	}

	##############################################################################

?>
