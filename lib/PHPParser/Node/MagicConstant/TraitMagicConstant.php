<?php

namespace PHPParser\Node\MagicConstant;

/**
 * Constructs a __TRAIT__ const node
 */
class TraitMagicConstant extends MagicConstantAbstract
{
    public function __toString(){
    	return '__TRAIT__';
    }
}
