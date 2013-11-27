<?php
	include 'colors.php';
	class Expectation {
		public static $testValue;
		private static $colors;
		private static $asIntended=true;
		public function __construct($val){
			static::$testValue = $val;
			static::$colors = new Colors();
		}

		public static function toBe($val){
			static::judge($val == static::$testValue,
				"expected ".print_r(static::$testValue, true)." but got ".print_r($val, true),
				"did not expect ".print_r($val, true).", but got it");
		}

		public static function not(){
			$expectation = new Expectation(static::$testValue);
			$expectation->notAsIntended();
			return $expectation;
		}

		private static function notAsIntended(){
			static::$asIntended = false;
		}

		public static function judge($eval, $msg, $unmsg){
			if(!static::$asIntended){
				$msg  = $unmsg;
				$eval = !$eval;
			}
			$eval ? static::success() : static::failure($msg);
			static::$asIntended = true;
		}

		private static function success(){
			print(static::$colors->getColoredString('.', 'green'));
		}

		private static function failure($msg){
			print static::$colors->getColoredString("\n$msg\n", 'red');
			debug_print_backtrace();
		}
	}
?>
