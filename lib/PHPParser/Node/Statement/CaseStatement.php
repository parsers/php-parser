<?php

namespace PHPParser\Node\Statement;

/**
 * @property null|\PHPParser\Node\Expression\Expression $cond  Condition (null for default)
 * @property \PHPParser\Node[]         $Statements Statements
 */
class CaseStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a case node.
     *
     * @param null|\PHPParser\Node\Expression\Expression $cond       Condition (null for default)
     * @param \PHPParser\Node[]         $Statements      Statements
     * @param array                    $attributes Additional attributes
     */
    public function __construct($cond, array $Statements = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'cond'  => $cond,
                'Statements' => $Statements,
            ),
            $attributes
        );
    }
}