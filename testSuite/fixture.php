<?php
	class Fixture {
		public static function get($name, $asArray=false){
			return json_decode(file_get_contents("fixtures/$name.json"), $asArray);
		}
	}
?>
