<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expr      $cond Condition
 * @property null|\PHPParser\Node\Expression\Expression $if   Expression for true
 * @property \PHPParser\Node\Expr      $else Expression for false
 */
class TernaryExpression extends Expression
{
    /**
     * Constructs a ternary operator node.
     *
     * @param \PHPParser\Node\Expr      $cond       Condition
     * @param null|\PHPParser\Node\Expression\Expression $if         Expression for true
     * @param \PHPParser\Node\Expr      $else       Expression for false
     * @param array                    $attributes Additional attributes
     */
    public function __construct(Expression $cond, $if, Expression $else, array $attributes = array()) {
        parent::__construct(
            array(
                'cond' => $cond,
                'if'   => $if,
                'else' => $else
            ),
            $attributes
        );
    }
}
