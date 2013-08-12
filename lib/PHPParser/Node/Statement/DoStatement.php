<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Expression\Expression $cond  Condition
 * @property \PHPParser\Node[]    $Statements Statements
 */
class DoStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a do while node.
     *
     * @param \PHPParser\Node\Expression\Expression $cond       Condition
     * @param \PHPParser\Node[]    $Statements      Statements
     * @param array               $attributes Additional attributes
     */
    public function __construct(\PHPParser\Node\Expression\Expression $cond, array $Statements = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'cond'  => $cond,
                'Statements' => $Statements,
            ),
            $attributes
        );
    }
}