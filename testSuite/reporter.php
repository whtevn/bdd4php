<?php
	class Reporter {
		private static $colors;
		public static function summary($title, $report, $expectations){
			static::$colors =  new Colors();

			foreach($expectations as $exp){
				if($exp->success){
					print(static::$colors->getColoredString('.', 'green'));
				}else{
					$msg = $exp->msg;
					print static::$colors->getColoredString("\n$msg\n", 'red');
					static::printBacktrace($exp->backtrace);
				}
			}

			echo("\n$title");
			$msg = "\n\tof ".sizeof($expectations)." expected results, ".sizeof($report['success'])."  succeeded and ".sizeof($report['failure'])." failed\n\n";

			$c = sizeof($report['failure'])>0 ? 'red' : 'green';
			print(static::$colors->getColoredString($msg, $c));
		}

		public static function printBacktrace($bts){
			foreach($bts as $bt){
				if(!preg_match('/\/testSuite/', $bt['file'])){
					switch($bt['class']){
						case 'Expectation':
							echo("\tExpectation failed on line ".$bt['line']." of ".$bt['file']."\n");
							break;
						case 'Scenario':
							if($bt['function']=='when'){
								echo("\tIn Scenario \"".$bt['args'][0]."\"\n");
							}else if($bt['function']=='the'){
								echo("\tWhile testing \"".$bt['args'][0]."\"\n");
							}else if($bt['function']=='beforeEach'){
								echo("\tUnder conditions \"".$bt['args'][0]."\"\n");
							}
							break;
					}
				}
			}
		}
	}
?>

