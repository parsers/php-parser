<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expression\Expression $expr  Expression
 * @property \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $class Class name
 */
class InstanceofExpression extends Expression
{
    /**
     * Constructs an instanceof check node.
     *
     * @param \PHPParser\Node\Expr                     $expr       Expression
     * @param \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $class      Class name
     * @param array                                   $attributes Additional attributes
     */
    public function __construct(Expression $expr, $class, array $attributes = array()) {
        parent::__construct(
            array(
                'expr'  => $expr,
                'class' => $class
            ),
            $attributes
        );
    }
}
