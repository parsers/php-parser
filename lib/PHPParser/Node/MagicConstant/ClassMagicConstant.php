<?php

namespace PHPParser\Node\MagicConstant;

/**
 * Constructs a __CLASS__ const node
 */
class ClassMagicConstant extends MagicConstantAbstract
{	
	public function __toString() {
		return '__CLASS__';
	}
}
