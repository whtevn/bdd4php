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
			Scenario::each(function($scene){
				$sceneFunc = $scene->runner->func;
				$sceneFunc($scene);
				return Reporter::Summarize($scene);
			});
		}

		private static function filenameList($files){
			return $files;
		}
	}
?>
