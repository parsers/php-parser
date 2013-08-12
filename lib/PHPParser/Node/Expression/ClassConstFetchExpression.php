<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $class Class name
 * @property string                                  $name  Constant name
 */
class ClassConstFetchExpression extends Expression
{
    /**
     * Constructs a class const fetch node.
     *
     * @param \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $class      Class name
     * @param string                                  $name       Constant name
     * @param array                                   $attributes Additional attributes
     */
    public function __construct($class, $name, array $attributes = array()) {
        parent::__construct(
            array(
                'class' => $class,
                'name'  => $name
            ),
            $attributes
        );
    }
}
