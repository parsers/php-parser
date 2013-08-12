<?php

namespace PHPParser\Token;

class SingleToken extends Token {
	protected $value;
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	public function getValue() {
		return $this->value;
	}
}
