<?php
	include 'fixture.php';	
	include 'expectation.php';	

	final class Scenario {
		private static $expectations=array();
		public static function when($title, $func) {
			$scene = new Scenario(function(){}); 
			$func($scene);
			$report = Scenario::reportOn(static::$expectations);
			echo("\n$title");
			echo("\n\tof ".sizeof(static::$expectations)." expected results, ".sizeof($report['success'])."  succeeded and ".sizeof($report['failure'])." failed\n\n");
			static::$expectations=array();
		}

		private static function reportOn($expectations){
			$report = array('success'=>array(), 'failure'=>array());
			foreach($expectations as $expectation){
				if($expectation->success){
					$report['success'][] = $expectation;
				}else{
					$report['failure'][] = $expectation;
				}
			}
			return $report;
		}

		public function beforeEach($before, $opt=null){
			if($opt){
				$this->doBeforeEach[] = $opt;
			}else{
				$this->doBeforeEach[] = $before;
			}
			return $this;
		}

		public function the($title, $func){
			if(!is_callable('expect')){
				function expect($val){
					return Scenario::runExpectation(new Expectation($val));
				}
			}
			foreach($this->doBeforeEach as $bfunc){
				$bfunc($this);
			}
			$func($this);
			return $this;
		}

		public static function runExpectation($exp){
			static::$expectations[]=$exp;
			return $exp;
		}

		private function __constructor($before){
			$this->doBeforeEach = array();
			$this->doBeforeEach[] = $before;
		}
	}
?>
