<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Expr[] $vars Variables
 */
class GlobalStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a global variables list node.
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