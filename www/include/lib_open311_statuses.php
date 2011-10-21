<?php

	##############################################################################

	function open311_statuses_get_statuses($args=array()){

		# TO DO: ordering
		# TO DO: caching
		# TO DO: string keys

		$sql = "SELECT * FROM Statuses";
		$rsp = db_fetch_paginated($sql, $args);

		return $rsp;
	}

	##############################################################################

	function open311_statuses_add_status($name, $description=''){

		$id = dbtickets_create();

		$status = array(
			'id' => $id,
			'name' => $name,
			'description' => $description,
		);

		$insert = array();

		foreach ($status as $k => $v){
			$insert[$k] = AddSlashes($v);
		}

		$rsp = db_insert('Statuses', $insert);

		if ($rsp['ok']){
			$rsp['status'] = $status;
		}

		return $rsp;
	}

	##############################################################################

?>
