<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $class Class name
 * @property \PHPParser\Node\Arg[]                    $args  Arguments
 */
class NewExpression extends Expression
{
    /**
     * Constructs a function call node.
     *
     * @param \PHPParser\Node\Name|\PHPParser\Node\Expression\Expression $class      Class name
     * @param \PHPParser\Node\Arg[]                    $args       Arguments
     * @param array                                   $attributes Additional attributes
     */
    public function __construct($class, array $args = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'class' => $class,
                'args'  => $args
            ),
            $attributes
        );
    }
    
    /**public function __toString() {
    	
    }**/
}
