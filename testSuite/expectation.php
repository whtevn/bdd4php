<?php
	class Expectation {
		private static $colors;
		public function __construct($val){
			static::$colors = new Colors();
			$this->testValue = $val;
			$this->asIntended = true;
		}

		public function toBeTypeOf($val){
			$this->judge($val == get_class($this->testValue),
				"expected object to be of type $val but was ".get_class($this->testValue),
				"did not expect object to be of type $val, but it was");
		}

		public function toBe($val){
			$this->judge($val == $this->testValue,
				"expected ".print_r($val, true)." but got ".print_r($this->testValue, true),
				"did not expect ".print_r($val, true).", but got it");
		}

		public function toHaveProperty($value){
			$this->judge(IsSet($this->testValue->{$value}),
				"expected object to have property $value, but it did not",
				"expected object not to have property $value, but it did");
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
