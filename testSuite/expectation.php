<?php
	include 'colors.php';
	class Expectation {
		private static $colors;
		public function __construct($val){
			$this->testValue = $val;
			$this->asIntended = true;
			static::$colors = new Colors();
		}

		public function toBe($val){
			$this->judge($val == $this->testValue,
				"expected ".print_r($this->testValue, true)." but got ".print_r($val, true),
				"did not expect ".print_r($val, true).", but got it");
		}

		public function not(){
			$this->notAsIntended();
			return $this;
		}

		private function notAsIntended(){
			$this->asIntended = false;
		}

		public function judge($eval, $msg, $unmsg){
			if(!$this->asIntended){
				$msg  = $unmsg;
				$eval = !$eval;
			}
			$eval ? static::success() : static::failure($msg);
			$this->asIntended = true;
		}

		private function success(){
			$this->success = true;
			print(static::$colors->getColoredString('.', 'green'));
		}

		private function failure($msg){
			$this->success = false;
			print static::$colors->getColoredString("\n$msg\n", 'red');
			debug_print_backtrace();
		}
	}
?>
