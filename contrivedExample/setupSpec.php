<?php
	$testSuite =__DIR__.'/../testSuite';
	include "$testSuite/runner.php";

	Runner::SuiteDir($testSuite);
	Runner::SpecDir(__DIR__);
	Runner::WatchFile('sampleSpec.php');
	Runner::CheckSpec();
?>
