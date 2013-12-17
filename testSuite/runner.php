<?php

	class Runner {
		private static $specDir;
		private static $suiteDir;
		private static $files=array();
		public static function SuiteDir($name){
			static::$suiteDir = $name;
			include_once "$name/scenario.php";
		}
		public static function SpecDir($name){
			static::$specDir = $name;
			chdir($name);
		}
		public static function WatchFile($loc){
			static::$files[] = $loc;
			include_once static::$specDir."/".$loc;
		}
		public static function CheckSpec(){
			$reporter = new Reporter();
			$reports = Scenario::each($reporter, function($scene, $reporter){
				$sceneFunc = $scene->runner->func;
				$sceneFunc($scene);
				return $reporter->summarize($scene);
			});


			$result = Colors::str("\n\nTL;DR: ", 'cyan');
			$pending = $successes = $failures = 0;
			$summary = '';
			foreach($reports as $report){
				$tldr = $reporter->tldr($report); 
				$pending += $tldr['pending'];
				$successes += $tldr['successes'];
				$failures += $tldr['failures'];
				$summary .= $tldr['record'];
			}
			$sumColor = $failures>0 ? 'red' : 'green';
			$result .= ($successes+$failures)." expectations ran"; 
			$result .= Colors::str("\n$successes succeeded and $failures failed", $sumColor);
			if($pending > 0){
				$result .= Colors::str("\n$pending expectations skipped", 'yellow');
			}
			echo($result."\n$summary\n\n");
		}

		private static function filenameList($files){
			return $files;
		}
	}
?>
