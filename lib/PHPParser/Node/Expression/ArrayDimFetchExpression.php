<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expr      $var Variable
 * @property null|\PHPParser\Node\Expression\Expression $dim Array index / dim
 */
class ArrayDimFetchExpression extends Expression
{
    /**
     * Constructs an array index fetch node.
     *
     * @param Expression        $var        Variable
     * @param null|Expression   $dim        Array index / dim
     * @param array             $attributes Additional attributes
     */
    public function __construct(Expression $var, Expression $dim = null, array $attributes = array()) {
        parent::__construct(
            array(
                'var' => $var,
                'dim' => $dim
            ),
            $attributes
        );
    }
}
