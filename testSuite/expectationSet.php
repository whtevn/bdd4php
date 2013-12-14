<?php
include 'expectation.php';	

function xexpect($val){
	return ExpectationSet::runExpectation($val, true);
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
		$es->pending = $ignore;
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

	public function xthe($title, $func){
		return $this->the($title, $func, true);
	}
	public function the($title, $func, $pending=false){
		return $this->scene->the($title, $func, $pending);
	}

	public static function runExpectation($exp, $pending=false){
		$set = end(static::$sets);
		if($set->pending){
			$pending = true;
		}
		$exp = new Expectation($exp, $pending);
		$set->expectations[] = $exp;

		return $exp;
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

}

?>
