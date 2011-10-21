<?php

	##############################################################################

	function open311_statuses_get_statuses($args=array()){

		$sql = "SELECT * FROM Statuses";
		$rsp = db_fetch_paginated($sql, $args);

		return $rsp;
	}

	##############################################################################

	function open311_statuses_is_valid_status($status_id){

		# TO DO: caching

		$enc_id = AddSlashes($status_id);

		$sql = "SELECT 1 FROM Services WHERE id='{$enc_id}'";
		return db_single(db_fetch($sql));
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
