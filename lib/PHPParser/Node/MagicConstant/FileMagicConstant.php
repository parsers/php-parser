<?php

namespace PHPParser\Node\MagicConstant;

/**
 * Constructs a __FILE__ const node
 */
class FileMagicConstant extends MagicConstantAbstract
{
    public function __toString() {
    	return '__FILE__';
    }
}
