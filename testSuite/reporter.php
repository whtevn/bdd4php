<?php
	class Reporter {
		private $counters=array();
		public function summarize($scene){
			$record  = array();
			echo(Colors::str("\n".$scene->title, 'cyan'));
			foreach($scene->expectationSet as $es){
				if($this->sizeIncreased('beforeSetSize', $es->before)){
					echo(Colors::str("\n\t".end($es->before)->title, 'cyan'));
				}
				if(!$es->pending && IsSet($es->errorSet['before']) && $this->sizeIncreased('beforeErrorSetSize', $es->errorSet['before'])){
					$err = end($es->errorSet['before']);
					echo(Colors::str("\n\t".$err['errorString']." during beforeEach\n\ton line ".$err['errorLine']." of ".$err['errorFile'], 'yellow'));
				}
				$success = true;
				$errors = array();
				foreach($es->expectations as $e){
					$record[] = $e;

					if(!$e->success && !$e->pending){
						$errors[] = $e->msg." \n\t\t\t\t(expectation ".$this->printBacktrace($e->backtrace).")";
					}
					if($success){
						$success = $e->success;
					}
				}
				if(!$es->pending){
					echo(Colors::str("\n\t\t".$es->title, ($e->success ? 'green' : 'red')));
				}
				if(sizeOf($errors)>0){
					foreach($errors as $err){
						echo(Colors::str("\n\t\t\t".$err, 'red'));
					}
				}
				if(!$es->pending && isSet($es->errorSet['during'][$es->id])){
					$duringError = $es->errorSet['during'][$es->id];
					echo(Colors::str("\n\t".$duringError['errorString']." during an expectation set\n\ton line ".$duringError['errorLine']." of ".$duringError['errorFile'], 'yellow'));
				}
			}

			return $record;
		}

		public static function tldr($record){
			$pending = $successes = $failures = 0;
			$result = "";
			foreach($record as $r){
				if($r->success && !$r->pending){
					$result .= Colors::str('.', 'green');
					$successes++;
				}else if(!$r->pending){
				  $result .= Colors::str('F', 'red');
					$failures++;
				}else{
					$pending++;
				}
			}
			$sumColor = $failures>0 ? 'red' : 'green';
			return array(
				'successes'=>$successes,
				'failures'=>$failures,
				'pending'=>$pending,
				'record'=>$result);
		}

		private function sizeIncreased($name, $thing){
			$oldSize = 0; 
			if(IsSet($this->counters[$name])){
				$oldSize = $this->counters[$name]; 
			}
			$this->counters[$name] = sizeof($thing);
			return sizeof($thing) > $oldSize;
		}

		public static function printBacktrace($bts){
			$result = '';
			foreach($bts as $bt){
				if(!preg_match('/\/testSuite/', $bt['file']) && IsSet($bt['class']) && $bt['class']=='Expectation'){
					$result .= "on line ".$bt['line']." of ".$bt['file'];
				}
			}
			return $result;
		}
	}
?>

