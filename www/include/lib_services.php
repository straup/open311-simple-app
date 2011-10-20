<?php

	function services_service_map($string_keys=0){

		# TO DO: caching
		# TO DO: string keys

		$sql = "SELECT * FROM Services";
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
