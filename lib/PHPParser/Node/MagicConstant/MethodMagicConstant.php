<?php

namespace PHPParser\Node\MagicConstant;

/**
 * Constructs a __METHOD__ const node
 */
class MethodMagicConstant extends MagicConstantAbstract
{
    public function __toString(){
    	return '__METHOD__';
    }
}
