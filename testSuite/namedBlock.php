<?php namespace BDD;
	class NamedBlock {
		public function __construct($title, $func=null){
			if(!$func){
				$func = $title;
				$title = null;
			}
			$this->title = $title;
			$this->func = $func;
		}
	}
?>

