<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expr[] $vars Variables
 */
class IssetExpression extends Expression
{
    /**
     * Constructs an array node.
     *
     * @param \PHPParser\Node\Expr[] $vars       Variables
     * @param array                 $attributes Additional attributes
     */
    public function __construct(array $vars, array $attributes = array()) {
        parent::__construct(
            array(
                'vars' => $vars
            ),
            $attributes
        );
    }
}
