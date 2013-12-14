<?php
	include 'fixture.php';	
	include 'colors.php';
	include 'expectation.php';	
	include 'expectationSet.php';	
	include 'reporter.php';	

	function xexpect($val){
		return Scenario::runExpectation($val, false);
	}

	function expect($val){
		return Scenario::runExpectation($val);
	}

	final class Scenario {
		private static $expectations=array();
		private static $colors;
		private static $rejected=false;
		public static function when($title, $func) {
			static::$colors =  new Colors();
			$scene = new Scenario(); 
			$scene->doBeforeEach = array();
			$scene->doAfterEach = array();
			$scene->expectationSet = array();
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
				if($expectation->pending){
					$report['pending'][] = $expectation;
				}else if($expectation->success){
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

		public function xxthe($title, $func){
			return $this;
		}
		public function xthe($title, $func){
			return $this->the($title, $func, true);
		}

		public function the($title, $func, $dontdoit=false){
			if($dontdoit){
				static::$rejected = true;
			}


			
			$this->expectationSet[] = $es =new ExpectationSet($title);

			foreach($this->doBeforeEach as $id => $bfunc){
				set_error_handler($es->generateErrorHandler('before', $id));
				$bfunc($this);
			}
			set_error_handler($es->generateErrorHandler('during', $es->id));
			$func($this);
			foreach($this->doAfterEach as $bfunc){
				set_error_handler($es->generateErrorHandler('after', $id));
				$bfunc($this);
			}
			static::$rejected = false;
			return $this;
		}

		public static function runExpectation($exp, $doRun=true){
			$pending = (!$doRun || static::$rejected);

			$exp = new Expectation($exp, $pending);

			static::$expectations[]=$exp;
			return $exp;
		}

	}
?>
