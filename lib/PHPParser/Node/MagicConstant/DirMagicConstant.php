<?php

namespace PHPParser\Node\MagicConstant;

/**
 * Constructs a __DIR__ const node
 */
class DirMagicConstant extends MagicConstantAbstract
{
    public function __toString() {
    	return '__DIR__';
    }
}
