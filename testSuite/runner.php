<?php
	include_once 'testSuite/scenario.php';

	//TODO:
	//	1. add command line options to include specific files
	//	2. include those files, then run all
	//	3. make a config file with default regex or file set

	Fixture::at('contrivedExample/fixtures');
	include 'contrivedExample/sampleSpec.php';

	Scenario::RunAll();
?>
