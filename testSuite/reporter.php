<?php
	class Reporter {
		private static $colors;
		private static $counters=array();
		public static function Summarize($scene){
			$record  = array();
			$colors =  new Colors();
			echo($colors->getColoredString($scene->title, 'cyan'));
			foreach($scene->expectationSet as $es){
				if(static::sizeIncreased('beforeSetSize', $es->before)){
					echo($colors->getColoredString("\n\t".end($es->before)->title, 'cyan'));
				}
				if(IsSet($es->errorSet['before']) && static::sizeIncreased('beforeErrorSetSize', $es->errorSet['before'])){
					$err = end($es->errorSet['before']);
					echo($colors->getColoredString("\n\t".$err['errorString']." during beforeEach\n\ton line ".$err['errorLine']." of ".$err['errorFile'], 'yellow'));
				}
				$success = true;
				$errors = array();
				foreach($es->expectations as $e){
					$record[] = $e;
					if(!$e->success){
						$errors[] = $e->msg." \n\t\t\t\t(expectation ".static::printBacktrace($e->backtrace).")";
					}
					if($success){
						$success = $e->success;
					}
				}
				echo($colors->getColoredString("\n\t\t".$es->title, ($e->success ? 'green' : 'red')));
				if(sizeOf($errors)>0){
					foreach($errors as $err){
						echo($colors->getColoredString("\n\t\t\t".$err, 'red'));
					}
				}
				if(isSet($es->errorSet['during'][$es->id])){
					$duringError = $es->errorSet['during'][$es->id];
					echo($colors->getColoredString("\n\t".$duringError['errorString']." during an expectation set\n\ton line ".$duringError['errorLine']." of ".$duringError['errorFile'], 'yellow'));
				}
			}

			$successes = $failures = 0;
			$result = "\n\n";
			foreach($record as $r){
				if($r->success){
					$result .= $colors->getColoredString('.', 'green');
					$successes++;
				}else{
				  $result .= $colors->getColoredString('F', 'red');
					$failures++;
				}
			}
			$sumColor = $failures>0 ? 'red' : 'green';
			echo($colors->getColoredString("\n\nTL;DR: ", 'cyan'));
			echo(sizeof($record)." exceptions ran");
			echo($colors->getColoredString("\n$successes succeeded and $failures failed", $sumColor));
			echo("$result\n\n");
		}

		private static function sizeIncreased($name, $thing){
			$oldSize = 0; 
			if(IsSet(static::$counters[$name])){
				$oldSize = static::$counters[$name]; 
			}
			static::$counters[$name] = sizeof($thing);
			return sizeof($thing) > $oldSize;
		}

		public static function printBacktrace($bts){
			$result = '';
			foreach($bts as $bt){
				if(!preg_match('/\/testSuite/', $bt['file']) && $bt['class']=='Expectation'){
					$result .= "on line ".$bt['line']." of ".$bt['file'];
				}
			}
			return $result;
		}
	}
?>

