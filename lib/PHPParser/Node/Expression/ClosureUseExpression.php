<?php

namespace PHPParser\Node\Expression;

/**
 * @property string $var   Name of variable
 * @property bool   $byRef Whether to use by reference
 */
class ClosureUseExpression extends Expression
{
    /**
     * Constructs a closure use node.
     *
     * @param string      $var        Name of variable
     * @param bool        $byRef      Whether to use by reference
     * @param array       $attributes Additional attributes
     */
    public function __construct($var, $byRef = false, array $attributes = array()) {
        parent::__construct(
            array(
                'var'   => $var,
                'byRef' => $byRef
            ),
            $attributes
        );
    }
}
