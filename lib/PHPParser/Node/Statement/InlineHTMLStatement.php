<?php

namespace PHPParser\Node\Statement;

/**
 * @property string $value String
 */
class InlineHTMLStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs an inline HTML node.
     *
     * @param string $value      String
     * @param array  $attributes Additional attributes
     */
    public function __construct($value, array $attributes = array()) {
        parent::__construct(
            array(
                'value' => $value,
            ),
            $attributes
        );
    }
}