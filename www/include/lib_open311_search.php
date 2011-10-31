<?php

	##############################################################################

	loadlib("open311_where");

	##############################################################################

	function open311_search($args=array()){

		return open311_search_mysql($args);
	}

	##############################################################################

	function open311_search_solr($args=array()){
		# please write me
	}

	##############################################################################

	# Note: this is here as a proof of concept. Unless you've got a tiny dataset
	# the number of indexes you'll require to make this performant will rapidly
	# get out of hand. Use something like Solr for search. Or frankly, your whole
	# database... (20111031/straup)

	function open311_search_mysql($args=array()){

		$query = array();

		if (isset($args['service_id'])){

			if (! is_array($args['service_id'])){
				$enc_id = AddSlashes($args['service_id']);
				$query[] = "service_id='{$enc_id}'";
			}

			else {
				$ids = array();

				foreach ($args['service_id'] as $id){
					$ids[] = "'" . AddSlashes($id) . "'";
				}

				$ids = implode(",", $ids);
				$query[] = "(service_id IN ($ids))";
			}
		}

		if (isset($args['incident_id'])){

			if (! is_array($args['incident_id'])){
				$enc_id = AddSlashes($args['incident_id']);
				$query[] = "incident_id='{$enc_id}'";
			}

			else {
				$ids = array();

				foreach ($args['incident_id'] as $id){
					$ids[] = "'" . AddSlashes($id) . "'";
				}

				$ids = implode(",", $ids);
				$query[] = "(incident_id IN ($ids))";
			}
		}

		if (isset($args['status_id'])){

			if (! is_array($args['status_id'])){
				$enc_id = AddSlashes($args['status_id']);
				$query[] = "status_id='{$enc_id}'";
			}

			else {
				$ids = array();

				foreach ($args['status_id'] as $id){
					$ids[] = "'" . AddSlashes($id) . "'";
				}

				$ids = implode(",", $ids);
				$query[] = "(status_id IN ($ids))";
			}
		}

		if (isset($args['created'])){

			$dates = open311_when_parse($args['created']);
		}

		if (isset($args['modified'])){

			$dates = open311_when_parse($args['modified']);
		}

		if (isset($args['where'])){

			$where = open311_where_parse($args['where']);
		}

		$sql = "SELECT * FROM Incidents WHERE " . implode(" AND ", $query);

		return db_fetch_paginated($sql, $args);
	}

	##############################################################################
?>
