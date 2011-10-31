<?php

	##############################################################################

	function open311_where_map(){

		# put me in a database? feels like overkill...

		$map = array(
			'bbox' => array(
				'description' => 'Search for incident reports by geographic extent',
				"example" => "bbox:37.788,-122.344,37.857,-122.256"
			),
			'zip' => array(
				'description' => 'Search for incident reports by zip code',
				"example" => "zip:94110"
			),
		);

		return $map;
	}

	##############################################################################

	function open311_where_is_valid_prefix($str){

		$map = open311_where_map();

		list($prefix, $ignore) = explode(":", $str, 2);

		if (! isset($map[$prefix])){
			return 0;
		}

		return 1;
	}

	##############################################################################

	function open311_where_parse($str){
		# please write me
	}

	##############################################################################
?>
