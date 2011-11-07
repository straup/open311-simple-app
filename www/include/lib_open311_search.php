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
				$query[] = "id='{$enc_id}'";
			}

			else {
				$ids = array();

				foreach ($args['incident_id'] as $id){
					$ids[] = "'" . AddSlashes($id) . "'";
				}

				$ids = implode(",", $ids);
				$query[] = "(id IN ($ids))";
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

		# this needs a bit more thought than I can give it right now
		# (20111031/straup)

		if (isset($args['created'])){

			list($start, $stop) = open311_when_parse($args['created']);

			if ($start == $stop){
				$enc_start = AddSlashes($start);
				$query[] = "created='{$enc_start}'";
			}

			else if ($start && $stop){
				$enc_start = AddSlashes($start);
				$enc_stop = AddSlashes($stop);
				$query[] = "created BETWEEN '{$enc_start}' AND '{$enc_stop}'";
			}

			else {}
		}

		if (isset($args['modified'])){

			list($start, $stop) = open311_when_parse($args['modified']);

			if ($start == $stop){
				$enc_start = AddSlashes($start);
				$query[] = "last_modified='{$enc_start}'";
			}

			else if ($start && $stop){
				$enc_start = AddSlashes($start);
				$enc_stop = AddSlashes($stop);
				$query[] = "last_modified BETWEEN '{$enc_start}' AND '{$enc_stop}'";
			}

			else {}
		}


		if (isset($args['where'])){

			if ($where = open311_where_parse($args['where'])){
				$query[] = $where;
			}
		}

		$sql = "SELECT * FROM Incidents WHERE " . implode(" AND ", $query);

		return db_fetch_paginated($sql, $args);
	}

	##############################################################################

?>
