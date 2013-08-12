<?php

namespace PHPParser\Node\MagicConstant;

use PHPParser\Node\Scalar\Scalar;

abstract class MagicConstantAbstract extends Scalar
{
    /**
     * Constructs a magic constant node
     *
     * @param array $attributes Additional attributes
     */
    public function __construct(array $attributes = array()) {
        parent::__construct(array(), $attributes);
    }
}
