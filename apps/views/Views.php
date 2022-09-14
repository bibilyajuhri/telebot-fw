<?php

namespace apps\views;

class Views{
	function __construct($var){
		foreach ($var as $key => $value) {
			$this->$key = $value;
		}
	}
}