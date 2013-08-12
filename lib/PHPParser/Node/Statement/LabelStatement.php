<?php

namespace PHPParser\Node\Statement;

/**
 * @property string $name Name
 */
class LabelStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a label node.
     *
     * @param string $name       Name
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