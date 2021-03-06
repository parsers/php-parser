<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expression\Expression $left  The left hand side expression
 * @property \PHPParser\Node\Expression\Expression $right The right hand side expression
 */
class SmallerExpression extends Expression
{
    /**
     * Constructs a smaller than comparison node.
     *
     * @param \PHPParser\Node\Expression\Expression $left       The left hand side expression
     * @param \PHPParser\Node\Expression\Expression $right      The right hand side expression
     * @param array               $attributes Additional attributes
     */
    public function __construct(Expression $left,Expression $right, array $attributes = array()) {
        parent::__construct(
            array(
                'left'  => $left,
                'right' => $right
            ),
            $attributes
        );
    }
}
