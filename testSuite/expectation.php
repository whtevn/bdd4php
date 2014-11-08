<?php namespace BDD;
	class Expectation {
		private static $colors;
		public function __construct($val, $pending=false){
			static::$colors = new Colors();
			$this->testValue = $val;
			$this->asIntended = true;
			$this->pending = $pending;
		}

		public function toBeTypeOf($val){
			$this->judge($val == get_class($this->testValue),
				"expected object to be of type $val but was ".get_class($this->testValue),
				"did not expect object to be of type $val, but it was");
		}

		public function toEqual($val){
			$this->judge($val == $this->testValue,
				"expected ".print_r($val, true)." but got ".print_r($this->testValue, true),
				"did not expect ".print_r($val, true).", but got it");
		}

		public function toBe($val){
			$this->judge($val === $this->testValue,
				"expected ".print_r($val, true)." but got ".print_r($this->testValue, true),
				"did not expect ".print_r($val, true).", but got it");
		}

		public function toBeTimestamp($val){
			$val = strtotime($val);
			$this->judge($val == $this->testValue,
				"expected ".strftime("%m/%d/%Y %l:%M:%S %p", $val)." but got ".strftime("%m/%d/%Y %l:%M:%S %p", $this->testValue),
				"did not expect ".strftime("%m/%d/%Y %l:%M:%S %p", $val).", but got it");
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
			
			$this->success = $eval;
			if($this->success){
				$msg = "success";
			}
			$this->msg=$msg;
			$this->backtrace=debug_backtrace();
		}
	}
?>
