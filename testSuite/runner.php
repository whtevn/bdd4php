<?php
	include_once 'testSuite/scenario.php';

	$args = $argv;

	array_shift($args);
	foreach($args as $loc){
		include_once $loc;
	}

	Scenario::RunAll();
?>
