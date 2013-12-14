<?php
	include 'expectation.php';	

	function xexpect($val){
		return ExpectationSet::runExpectation($val, false);
	}

	function expect($val){
		return ExpectationSet::runExpectation($val);
	}

class ExpectationSet {
	private static $sets = array();
	public function __construct($title, $scene){
		$this->title=$title;
		$this->id=uniqid('expectation');
		$this->errorSet = array();
		$this->expectations = array();
		$this->before = $scene->doBeforeEach;
		$this->after  = $scene->doAfterEach;
		$this->scene  = $scene;
	}

	public static function Run($scene, $title, $func, $ignore=false){
		$es = new ExpectationSet($title, $scene);
		static::$sets[] = $es;
		foreach($es->before as $id => $b){
			set_error_handler($es->generateErrorHandler('before', $id));
			$bfunc = $b->func;
			$bfunc($es->scene);
			restore_error_handler();
		}
		set_error_handler($es->generateErrorHandler('during', $es->id));
		$func($es->scene);
		restore_error_handler();
		foreach($es->after as $id=>$a){
			set_error_handler($es->generateErrorHandler('after', $id));
			$afunc = $a->func;
			$afunc($es->scene);
			restore_error_handler();
		}
		return $es;
	}

	public function the($title, $func, $dontdoit=false){
		return $this->scene->the($title, $func, $dontdoit);
	}

	public static function runExpectation($exp, $doRun=true){
		$pending = !$doRun;
		$exp = new Expectation($exp, $pending);
		$set = end(static::$sets);
		$set->expectations[] = $exp;

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
