<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Expression\Expression $expr Expression
 */
class ThrowStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a throw node.
     *
     * @param \PHPParser\Node\Expression\Expression $expr       Expression
     * @param array               $attributes Additional attributes
     */
    public function __construct(\PHPParser\Node\Expression\Expression $expr, array $attributes = array()) {
        parent::__construct(
            array(
                'expr' => $expr,
            ),
            $attributes
        );
    }
}