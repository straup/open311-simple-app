<?php

	##############################################################################

	function open311_where_map(){

		# put me in a database? feels like overkill...

		$map = array(
			array(
				'prefix' => 'bbox',
				'description' => 'Search for incident reports by geographic extent',
				"example" => "bbox:37.788,-122.344,37.857,-122.256"
			),
			array(
				'prefix' => 'zip',
				'description' => 'Search for incident reports by zip code',
				"example" => "zip:94110"
			),
		);

		return $map;
	}

	##############################################################################
?>
