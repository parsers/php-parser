<?php

namespace PHPParser\Node\Expression;

/**
 * @property null|\PHPParser\Node\Expression\Expression $expr Expression
 */
class ExitExpression extends Expression
{
    /**
     * Constructs an exit() node.
     *
     * @param null|\PHPParser\Node\Expression\Expression $expr       Expression
     * @param array                    $attributes Additional attributes
     */
    public function __construct(Expression $expr = null, array $attributes = array()) {
        parent::__construct(
            array(
                'expr' => $expr
            ),
            $attributes
        );
    }
}
