<?php
	include 'fixture.php';	
	include 'expectation.php';	

	final class Scenario {
		public static function when($title) {
			return new Scenario(function(){});
		}

		public function beforeEach($before){
			$scene = new Scenario;
			$scene->doBeforeEach = $before;
			return $scene;
		}

		public function it($title, $func){
			if(!is_callable('expect')){
				function expect($val){
					return new Expectation($val);
				}
			}
			$dbe = $this->doBeforeEach;
			$dbe($this);
			$func($this);
			return $this;
		}

		private function __constructor($before){
			$this->addMethod('doBeforeEach', $before);
		}
	}
?>
