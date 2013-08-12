<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expression\Expression $expr Expression
 */
class ErrorSuppressExpression extends Expression
{
    /**
     * Constructs an error suppress node.
     *
     * @param \PHPParser\Node\Expression\Expression $expr       Expression
     * @param array               $attributes Additional attributes
     */
    public function __construct(Expression $expr, array $attributes = array()) {
        parent::__construct(
            array(
                'expr' => $expr
            ),
            $attributes
        );
    }
}
