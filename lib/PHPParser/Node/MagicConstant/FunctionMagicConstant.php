<?php

namespace PHPParser\Node\MagicConstant;

/**
 * Constructs a __FUNCTION__ const node
 */
class FunctionMagicConstant extends MagicConstantAbstract
{
    public function __toString(){
    	return '__FUNCTION__';
    }
}
