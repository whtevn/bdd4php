<?php
	include 'fixture.php';	
	include 'colors.php';
	include 'expectation.php';	

	final class Scenario {
		private static $expectations=array();
		private static $colors;
		public static function when($title, $func) {
			static::$colors =  new Colors();
			$scene = new Scenario(); 
			$scene->doBeforeEach = array();
			$scene->doAfterEach = array();
			$func($scene);

			$report = Scenario::reportOn(static::$expectations);
			Scenario::printReport($title, $report, $expectations);

			static::resetExpectations();
		}

		private static function resetExpectations(){
			static::$expectations=array();
		}

		public static function printReport($title, $report, $expectations){
			echo("\n$title");
			$msg = "\n\tof ".sizeof(static::$expectations)." expected results, ".sizeof($report['success'])."  succeeded and ".sizeof($report['failure'])." failed\n\n";

			$c = sizeof($report['failure'])>0 ? 'red' : 'green';
			print(static::$colors->getColoredString($msg, $c));
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
		public function afterEach($after, $opt=null){
			if($opt){
				$this->doAfterEach[] = $opt;
			}else{
				$this->doAfterEach[] = $after;
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
			foreach($this->doAfterEach as $bfunc){
				$bfunc($this);
			}
			return $this;
		}

		public static function runExpectation($exp){
			static::$expectations[]=$exp;
			return $exp;
		}

	}
?>
