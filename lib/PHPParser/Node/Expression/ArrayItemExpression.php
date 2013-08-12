<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expr      $value Value
 * @property null|\PHPParser\Node\Expression\Expression $key   Key
 * @property bool                     $byRef Whether to assign by reference
 */
class ArrayItemExpression extends Expression
{
    /**
     * Constructs an array item node.
     *
     * @param \PHPParser\Node\Expr      $value      Value
     * @param null|\PHPParser\Node\Expression\Expression $key        Key
     * @param bool                     $byRef      Whether to assign by reference
     * @param array                    $attributes Additional attributes
     */
    public function __construct(Expression $value, Expression $key = null, $byRef = false, array $attributes = array()) {
        parent::__construct(
            array(
                'key'   => $key,
                'value' => $value,
                'byRef' => $byRef
            ),
            $attributes
        );
    }
}
