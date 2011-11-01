<?php

	##############################################################################

	function open311_when_is_valid_date($str){
		return strtotime($str);
	}

	##############################################################################

	function open311_when_parse($str){

		list($start, $stop) = explode(";", $str, 2);

		# TO DO: be a lot smarter about dates; for example
		# '2011' should know to span the entire year

		$dates = array();

		foreach (array($start, $stop) as $dt){

			# see above

			$ts = strtotime($dt);
			$dt = gmdate('Y-m-d H:i:s', $ts);
			$dates[] = $dt;
		}

		return $dates;
	}

	##############################################################################
?>
