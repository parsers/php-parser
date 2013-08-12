<?php

namespace PHPParser\Node;

use PHPParser\Node\Expression\Expression;

/**
 * @property string              $name  Name
 * @property \PHPParser\Node\Expression\Expression $value Value
 */
class ConstNode extends NodeAbstract
{
    /**
     * Constructs a const node for use in class const and const statements.
     *
     * @param string              $name       Name
     * @param \PHPParser\Node\Expression\Expression $value      Value
     * @param array               $attributes Additional attributes
     */
    public function __construct($name, Expression $value, array $attributes = array()) {
        parent::__construct(
            array(
                'name'  => $name,
                'value' => $value,
            ),
            $attributes
        );
    }
}