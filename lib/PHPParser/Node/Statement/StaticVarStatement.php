<?php

namespace PHPParser\Node\Statement;

/**
 * @property string                   $name    Name
 * @property null|\PHPParser\Node\Expression\Expression $default Default value
 */
class StaticVarStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a static variable node.
     *
     * @param string                   $name       Name
     * @param null|\PHPParser\Node\Expression\Expression $default    Default value
     * @param array                    $attributes Additional attributes
     */
    public function __construct($name, \PHPParser\Node\Expression\Expression $default = null, array $attributes = array()) {
        parent::__construct(
            array(
                'name'    => $name,
                'default' => $default,
            ),
            $attributes
        );
    }
}