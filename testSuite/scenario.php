<?php
	include 'fixture.php';	
	include 'colors.php';
	include 'expectation.php';	
	include 'reporter.php';	

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
			Reporter::summary($title, $report, static::$expectations);

			static::resetExpectations();
		}

		private static function resetExpectations(){
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
