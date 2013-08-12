<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $class Class name
 * @property string|\PHPParser\Node\Expr              $name  Method name
 * @property \PHPParser\Node\Arg[]                    $args  Arguments
 */
class StaticCallExpression extends Expression
{
    /**
     * Constructs a static method call node.
     *
     * @param \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $class      Class name
     * @param string|\PHPParser\Node\Expr              $name       Method name
     * @param \PHPParser\Node\Arg[]                    $args       Arguments
     * @param array                                   $attributes Additional attributes
     */
    public function __construct($class, $name, array $args = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'class' => $class,
                'name'  => $name,
                'args'  => $args
            ),
            $attributes
        );
    }
}
