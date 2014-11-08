<?php namespace BDD;
	class Fixture {
		private static $loc;
		public static function get($name, $asArray=false){
			return json_decode(file_get_contents(static::$loc."/$name.json"), $asArray);
		}
		public static function at($loc){
			static::$loc = $loc;
		}
	}
?>
