<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expression\Expression $var  Variable
 * @property \PHPParser\Node\Expression\Expression $expr Expression
 */
class AssignMinusExpression extends Expression
{
    /**
     * Constructs an assignment with minus node.
     *
     * @param \PHPParser\Node\Expression\Expression $var        Variable
     * @param \PHPParser\Node\Expression\Expression $expr       Expression
     * @param array               $attributes Additional attributes
     */
    public function __construct(Expression $var, Expression $expr, array $attributes = array()) {
        parent::__construct(
            array(
                'var'  => $var,
                'expr' => $expr
            ),
            $attributes
        );
    }
}
