<?php

namespace PHPParser\Node\MagicConstant;

/**
 * Constructs a __LINE__ const node
 */
class LineMagicConstant extends MagicConstantAbstract
{
    public function __toString() {
    	return '__LINE__';
    }
}
