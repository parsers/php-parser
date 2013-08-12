<?php

namespace PHPParser\Node\Statement;

/**
 * @property string $name Name of label to jump to
 */
class GotoStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a goto node.
     *
     * @param string $name       Name of label to jump to
     * @param array  $attributes Additional attributes
     */
    public function __construct($name, array $attributes = array()) {
        parent::__construct(
            array(
                'name' => $name,
            ),
            $attributes
        );
    }
}