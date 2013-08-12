<?php

namespace PHPParser\Node\Statement;

/**
 * @property string              $key   Key
 * @property \PHPParser\Node\Expression\Expression $value Value
 */
class DeclareDeclareStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a declare key=>value pair node.
     *
     * @param string              $key        Key
     * @param \PHPParser\Node\Expression\Expression $value      Value
     * @param array               $attributes Additional attributes
     */
    public function __construct($key, \PHPParser\Node\Expression\Expression $value, array $attributes = array()) {
        parent::__construct(
            array(
                'key'   => $key,
                'value' => $value,
            ),
            $attributes
        );
    }
}