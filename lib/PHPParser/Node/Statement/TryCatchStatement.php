<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node[]            $Statements        Statements
 * @property \PHPParser\Node\Statement_Catch[] $catches      Catches
 * @property \PHPParser\Node[]            $finallyStatements Finally statements
 */
class TryCatchStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a try catch node.
     *
     * @param \PHPParser\Node[]            $Statements        Statements
     * @param \PHPParser\Node\Statement_Catch[] $catches      Catches
     * @param \PHPParser\Node[]            $finallyStatements Finally statements (null means no finally clause)
     * @param array|null                  $attributes   Additional attributes
     */
    public function __construct(array $Statements, array $catches, array $finallyStatements = null, array $attributes = array()) {
        if (empty($catches) && null === $finallyStatements) {
            throw new \PHPParser\Error\Error('Cannot use try without catch or finally', $attributes['startLine']);
        }

        parent::__construct(
            array(
                'Statements'        => $Statements,
                'catches'      => $catches,
                'finallyStatements' => $finallyStatements,
            ),
            $attributes
        );
    }
}