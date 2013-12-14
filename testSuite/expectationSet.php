<?php

class ExpectationSet {
	public function __construct($title){
		$this->title=$title;
		$this->id=uniqid('expectation');
		$this->errorSet = array();
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
}

?>
