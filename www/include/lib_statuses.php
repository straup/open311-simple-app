<?php

	function statuses_status_map($string_keys){

		# TO DO: caching
		# TO DO: string keys

		$sql = "SELECT * FROM Statuses";
		$rsp = db_fetch($sql);

		$map = array();

		foreach ($rsp['rows'] as $row){
			$service_id = $row['id'];
			unset($row['id']);
			$map[$service_id] = $row;
		}

		return $map;
	}

?>
