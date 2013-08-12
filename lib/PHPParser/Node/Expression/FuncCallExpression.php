<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $name Function name
 * @property \PHPParser\Node\Arg[]                    $args Arguments
 */
class FuncCallExpression extends Expression
{
    /**
     * Constructs a function call node.
     *
     * @param \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $name       Function name
     * @param \PHPParser\Node\Arg[]                    $args       Arguments
     * @param array                                   $attributes Additional attributes
     */
    public function __construct($name, array $args = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'name' => $name,
                'args' => $args
            ),
            $attributes
        );
    }
}
