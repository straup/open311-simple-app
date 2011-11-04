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

	# Not sure I like this returning SQL but it will do for now...
	# (20111031/straup)

	function open311_where_parse($str){

		list ($prefix, $coords) = explode(":", $str, 2);

		if ($prefix == 'bbox'){

			list($swlat, $swlon, $nelat, $nelon) = $coords;

			$enc_swlat = AddSlashes($swlat);
			$enc_swlon = AddSlashes($swlon);
			$enc_nelat = AddSlashes($nelat);
			$enc_nelon = AddSlashes($nelon);

			$query = array(
				"latitude BETWEEN '{$enc_swlat}' AND '{$enc_nelat}'",
				"longitude BETWEEN '{$enc_swlon}' AND '{$enc_nelon}'",
			);

			return "(" . implode(" AND ", $query) . ")";
		}

		if ($prefix == 'zip'){
			$enc_zip = AddSlashes($coords);
			return "zipcode='{$enc_zip}'";
		}

		return;
	}

	##############################################################################
?>
