<?php

namespace PHPParser\Node\Expression;

/**
 * @property Expr_ArrayItemExpression[] $items Items
 */
class ArrayExpression extends Expression
{
    /**
     * Constructs an array node.
     *
     * @param ArrayItemExpression[] $items      Items of the array
     * @param array                           $attributes Additional attributes
     */
    public function __construct(array $items = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'items' => $items
            ),
            $attributes
        );
    }
}
