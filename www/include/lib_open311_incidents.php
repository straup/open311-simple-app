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
			$incident['id'] = dbtickets_create(64);
		}

		if (! isset($incident['created'])){
			$incident['created'] = $dt;
		}

		if (! isset($incident['status_id'])){
			$incident['status_id'] = 1;	# FIX ME: read from database?
		}

		$incident['last_modified'] = $dt;

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

	function open311_incidents_add_note(&$incident, $note){

		$id = dbtickets_create(64);
		$now = time();

		$note['id'] = $id;
		$note['created'] = $now;
		$note['last_modified'] = $now;

		$insert = array();

		foreach ($note as $k => $v){
			$insert[$k] = AddSlashes($v);
		}

		$rsp = db_insert('IncidentsNotes', $insert);

		if ($rsp['ok']){
			$rsp['note'] = $note;
		}

		return $rsp;
	}

	##############################################################################

	function open311_incidents_get_notes(&$incidents, $only_public=1){

		$sql = "SELECT * FROM IncidentsNotes WHERE incident_id='{$enc_id}'";

		if ($only_public){
			$sql .= " AND public=1";
		}

		$sql .= " ORDER BY created DESC";

		# paginate?
		return db_fetch($sql);
	}

	##############################################################################
?>
