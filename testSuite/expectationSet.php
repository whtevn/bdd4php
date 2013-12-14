<?php
	include 'expectation.php';	

	function xexpect($val){
		return ExpectationSet::runExpectation($val, false);
	}

	function expect($val){
		return ExpectationSet::runExpectation($val);
	}

class ExpectationSet {
	public function __construct($title){
		$this->title=$title;
		$this->id=uniqid('expectation');
		$this->errorSet = array();
		$this->expectations = array();
	}

	public static function Run($scene, $title, $func, $ignore=false){
		$es = new ExpectationSet($title);
		$es->before = $scene->doBeforeEach;
		$es->after  = $scene->doAfterEach;
		return $es->the($title, $func);
	}

	public function the($title, $func, $dontdoit=false){
		/*foreach($this->before as $id => $bfunc){
			set_error_handler($this->generateErrorHandler('before', $id));
			$bfunc($scene);
		}
		set_error_handler($this->generateErrorHandler('during', $es->id));
		$func($scene);
		foreach($this->after as $bfunc){
			set_error_handler($this->generateErrorHandler('after', $id));
			$bfunc($scene);
		}*/
		return $this;
	}

	public static function runExpectation($exp, $doRun=true){
		$pending = !$doRun;
		$exp = new Expectation($exp, $pending);

		static::$expectations[]=$exp;
		return $exp;
	}

	public function setError($error, $time, $id){
		if(!IsSet($this->errorSet[$time])){
			$this->errorSet[$time] = array();
		}
	}

	public function generateErrorHandler($time, $id=0){
		$es = $this;
		return function($errno, $errstr, $errfile, $errline) use ($es, $time, $id) {
			$es->errorSet[$time][$id] =
				array(
					'errorNumber'=>$errno,
					'errorString'=>$errstr,
					'errorFile'=>$errfile,
					'errorLine'=>$errline);
		};
	}

	public function expect($exp){
		$exp = new Expectation($exp);
		$this->expectations[] = $exp;

		return $exp;
	}
}

?>
