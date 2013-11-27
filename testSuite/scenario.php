<?php
	include 'fixture.php';	
	include 'expectation.php';	

	final class Scenario {
		private static $expectations=array();
		public static function when($title, $func) {
			$func(new Scenario(function(){}));
			$report = Scenario::reportOn(static::$expectations);
			echo("\n\nof ".sizeof(static::$expectations)." expected results, ".sizeof($report['success'])."  succeeded and ".sizeof($report['failure'])." failed\n\n");
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

		public function beforeEach($before){
			$scene = new Scenario;
			$scene->doBeforeEach = $before;
			return $scene;
		}

		public function the($title, $func){
			if(!is_callable('expect')){
				function expect($val){
					return Scenario::runExpectation(new Expectation($val));
				}
			}
			$dbe = $this->doBeforeEach;
			$dbe($this);
			$func($this);
			return $this;
		}

		public static function runExpectation($exp){
			static::$expectations[]=$exp;
			return $exp;
		}

		private function __constructor($before){
			$this->addMethod('doBeforeEach', $before);
		}
	}
?>
