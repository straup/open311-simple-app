<?php

	loadlib("open311_statuses");

	##############################################################################

	function api_open311_statuses_getList(){

		$args = array();
		api_utils_ensure_pagination_args($args);

		$rsp = open311_statuses_get_statuses($args);

		$out = array(
			'statuses' => $rsp['rows'],
		);

		api_utils_ensure_pagination_results($out, $rsp['pagination']);
		return api_output_ok($out);
	}

	##############################################################################

	# open311.statuses.getInfo ... necessary?

?>
