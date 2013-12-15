<?php
	include 'fixture.php';	
	include 'colors.php';
	include 'expectationSet.php';	
	include 'reporter.php';	
	include 'namedBlock.php';	

	final class Scenario {
		public static $scenarios = array();
		public static function when($title, $func) {
			$scene = new Scenario(); 
			$scene->doBeforeEach = array();
			$scene->doAfterEach = array();
			$scene->expectationSet = array();
			$scene->title = $title;
			$scene->runner = new NamedBlock($title, $func);
			static::$scenarios[] = $scene;
		}

		public function beforeEach($before, $opt=null){
			$this->doBeforeEach[] =new NamedBlock($before, $opt);
			return $this;
		}

		public function afterEach($after, $opt=null){
			$this->doBeforeEach[] = new NamedBlock($after, $opt);
			return $this;
		}

		public function xthe($title, $func){
			return $this->the($title, $func, true);
		}

		public function the($title, $func, $pending=false){
			return $this->expectationSet[] = ExpectationSet::Run($this, $title, $func, $pending);
		}

		public static function each($func){
			$array = array();
			foreach(static::$scenarios as $scene){
				$array[] = $func($scene);
			}
			return $array;
		}
	}
?>
