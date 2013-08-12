<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expression\Expression $var Variable
 */
class PreIncExpression extends Expression
{
    /**
     * Constructs a pre increment node.
     *
     * @param \PHPParser\Node\Expression\Expression $var        Variable
     * @param array               $attributes Additional attributes
     */
    public function __construct(Expression $var, array $attributes = array()) {
        parent::__construct(
            array(
                'var' => $var
            ),
            $attributes
        );
    }
}
