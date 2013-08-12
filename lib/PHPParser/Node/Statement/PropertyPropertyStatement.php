<?php

namespace PHPParser\Node\Statement;

/**
 * @property string                   $name    Name
 * @property null|\PHPParser\Node\Expression\Expression $default Default
 */

use PHPParser\Node\Expression\Expression;

class PropertyPropertyStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a class property node.
     *
     * @param string                   $name       Name
     * @param null|\PHPParser\Node\Expression\Expression $default    Default value
     * @param array                    $attributes Additional attributes
     */
    public function __construct($name, Expression $default = null, array $attributes = array()) {
        parent::__construct(
            array(
                'name'    => $name,
                'default' => $default,
            ),
            $attributes
        );
    }
}