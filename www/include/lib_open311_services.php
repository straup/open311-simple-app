<?php

	##############################################################################

	function open311_services_get_services($args=array()){

		$sql = "SELECT * FROM Services";
		$rsp = db_fetch_paginated($sql);

		return $rsp;
	}

	##############################################################################

	function open311_services_is_valid_service($service_id){

		# TO DO: caching

		$enc_id = AddSlashes($service_id);

		$sql = "SELECT 1 FROM Services WHERE id='{$enc_id}'";
		return db_single(db_fetch($sql));
	}

	##############################################################################

	function open311_services_add_service($name, $description=''){

		$id = dbtickets_create();

		$service = array(
			'id' => $id,
			'name' => $name,
			'description' => $description,
		);

		$insert = array();

		foreach ($service as $k => $v){
			$insert[$k] = AddSlashes($v);
		}

		$rsp = db_insert('Services', $insert);

		if ($rsp['ok']){
			$rsp['service'] = $service;
		}

		return $rsp;
	}

	##############################################################################
?>
