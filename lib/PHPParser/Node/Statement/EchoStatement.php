<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Expr[] $exprs Expressions
 */
class EchoStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs an echo node.
     *
     * @param \PHPParser\Node\Expr[] $exprs      Expressions
     * @param array                 $attributes Additional attributes
     */
    public function __construct(array $exprs, array $attributes = array()) {
        parent::__construct(
            array(
                'exprs' => $exprs,
            ),
            $attributes
        );
    }
}