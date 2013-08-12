<?php

namespace PHPParser\Node\MagicConstant;

/**
 * Constructs a __NAMESPACE__ const node
 */
class NamespaceMagicConstant extends MagicConstantAbstract
{
    public function __toString(){
    	return '__NAMESPACE__';
    }
}
