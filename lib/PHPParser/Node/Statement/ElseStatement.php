<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node[] $Statements Statements
 */
class ElseStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs an else node.
     *
     * @param \PHPParser\Node[] $Statements      Statements
     * @param array            $attributes Additional attributes
     */
    public function __construct(array $Statements = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'Statements' => $Statements,
            ),
            $attributes
        );
    }
}