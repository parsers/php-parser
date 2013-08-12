<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Statement_DeclareDeclare[] $declares List of declares
 * @property \PHPParser\Node[]                     $Statements    Statements
 */
class DeclareStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a declare node.
     *
     * @param \PHPParser\Node\Statement_DeclareDeclare[] $declares   List of declares
     * @param \PHPParser\Node[]                     $Statements      Statements
     * @param array                                $attributes Additional attributes
     */
    public function __construct(array $declares, array $Statements, array $attributes = array()) {
        parent::__construct(
            array(
                'declares' => $declares,
                'Statements'    => $Statements,
            ),
            $attributes
        );
    }
}