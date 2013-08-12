<?php

namespace PHPParser\Node\Expression;

/**
 * @property string|\PHPParser\Node\Expression\Expression $name Name
 */
class VariableExpression extends Expression
{
    /**
     * Constructs a variable node.
     *
     * @param string|\PHPParser\Node\Expression\Expression $name       Name
     * @param array                      $attributes Additional attributes
     */
    public function __construct($name, array $attributes = array()) {
        parent::__construct(
            array(
                 'name' => $name
            ),
            $attributes
        );
    }
}
