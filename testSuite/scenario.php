<?php
	include 'fixture.php';	
	include 'colors.php';
	include 'expectationSet.php';	
	include 'reporter.php';	


	final class Scenario {
		public static function when($title, $func) {
			$scene = new Scenario(); 
			$scene->doBeforeEach = array();
			$scene->doAfterEach = array();
			$scene->expectationSet = array();
			$scene->title = $title;
			$func($scene);
			Reporter::Summarize($scene);
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

		public function xthe($title, $func){
			return $this->the($title, $func, true);
		}

		public function the($title, $func, $dontdoit=false){
			return ExpectationSet::Run($this, $title, $func, $dontdoit);
		}


	}
?>
