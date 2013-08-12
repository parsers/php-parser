<?php

namespace PHPParser\Node;

/**
 * @property \PHPParser\Node\Expression\Expression $value Value to pass
 * @property bool                $byRef Whether to pass by ref
 */

class ArgNode extends NodeAbstract
{
    /**
     * Constructs a function call argument node.
     *
     * @param \PHPParser\Node\Expression\Expression $value      Value to pass
     * @param bool                $byRef      Whether to pass by ref
     * @param array               $attributes Additional attributes
     */
    public function __construct(\PHPParser\Node\Expression\Expression $value, $byRef = false, array $attributes = array()) {
        parent::__construct(
            array(
                'value' => $value,
                'byRef' => $byRef
            ),
            $attributes
        );
    }
}
