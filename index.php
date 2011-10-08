<?php
	session_start();
	
	# Imports
	require_once "system/healthq.class.php";
	
	# Fetch the address you've been sent
	$request = $_SERVER['QUERY_STRING'];
	$request = explode("/", $request);
	if ($request[count($request) - 1] == "") {
		array_pop($request);
	}
	$routine = array_shift($request);

	$healthQ = new healthQ($routine, $request, "settings.ini");
?>
