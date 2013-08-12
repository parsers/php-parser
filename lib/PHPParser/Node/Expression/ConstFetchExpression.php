<?php

namespace PHPParser\Node\Expression;

use PHPParser\Node\NameNode;

use \PHPParser\Node\Name;

/**
 * @property \PHPParser\Node\NameNode $name Constant name
 */
class ConstFetchExpression extends Expression
{
    /**
     * Constructs a const fetch node.
     *
     * @param \PHPParser\Node\NameNode $name       Constant name
     * @param array               $attributes Additional attributes
     */
    public function __construct(NameNode $name, array $attributes = array()) {
        parent::__construct(
            array(
                'name'  => $name
            ),
            $attributes
        );
    }
}
