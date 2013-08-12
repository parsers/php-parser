<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Expr        $var  Variable holding object
 * @property string|\PHPParser\Node\Expression\Expression $name Method name
 * @property \PHPParser\Node\Arg[]       $args Arguments
 */
class MethodCallExpression extends Expression
{
    /**
     * Constructs a function call node.
     *
     * @param \PHPParser\Node\Expr        $var        Variable holding object
     * @param string|\PHPParser\Node\Expression\Expression $name       Method name
     * @param \PHPParser\Node\Arg[]       $args       Arguments
     * @param array                      $attributes Additional attributes
     */
    public function __construct(Expression $var, $name, array $args = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'var'  => $var,
                'name' => $name,
                'args' => $args
            ),
            $attributes
        );
    }
}
