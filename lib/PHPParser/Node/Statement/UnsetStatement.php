<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Expr[] $vars Variables to unset
 */
class UnsetStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs an unset node.
     *
     * @param \PHPParser\Node\Expr[] $vars       Variables to unset
     * @param array                 $attributes Additional attributes
     */
    public function __construct(array $vars, array $attributes = array()) {
        parent::__construct(
            array(
                'vars' => $vars,
            ),
            $attributes
        );
    }
}