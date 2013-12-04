<?php
	class Reporter {
		private static $colors;
		public static function summary($title, $report, $expectations){
			static::$colors =  new Colors();

			$failures = array();
			foreach($expectations as $exp){
				if(!$exp->pending && $exp->success){
					print(static::$colors->getColoredString('.', 'green'));
				}else if(!$exp->pending){
					print(static::$colors->getColoredString('F', 'red'));
					$msg = $exp->msg;
					$failures[] = $exp; 
				}
			}


			echo("\n$title");
			$totalRan = sizeof($report['success'])+sizeof($report['failure']);
			$msg = "\n\tof ".$totalRan." expected results, ".sizeof($report['success'])."  succeeded and ".sizeof($report['failure'])." failed\n";

			$c = sizeof($report['failure'])>0 ? 'red' : 'green';
			print(static::$colors->getColoredString($msg, $c));
			if(IsSet($report['pending']) && sizeof($report['pending']) > 0){
				$msg = "\t".sizeof($report['pending'])." pending expectations were not run\n";
				print(static::$colors->getColoredString($msg, 'yellow'));
			}
print("\n");
			foreach($failures as $exp){
				print(static::$colors->getColoredString($exp->msg, 'red')."\n");
				static::printBacktrace($exp->backtrace);
				print("\n");
			}
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
						default:
							// stack traces that did not come from the testSuite
							break;
					}
				}
			}
		}
	}
?>

