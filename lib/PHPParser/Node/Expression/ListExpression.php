<?php

namespace PHPParser\Node\Expression;

/**
 * @property array $vars List of variables to assign to
 */
class ListExpression extends Expression
{
    /**
     * Constructs a list() destructuring node.
     *
     * @param array $vars       List of variables to assign to
     * @param array $attributes Additional attributes
     */
    public function __construct(array $vars, array $attributes = array()) {
        parent::__construct(
            array(
                'vars' => $vars,
            ),
            $attributes
        );
    }
}
