<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\NameNode $type  Class of exception
 * @property string              $var   Variable for exception
 * @property \PHPParser\Node[]    $Statements Statements
 */
class CatchStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a catch node.
     *
     * @param \PHPParser\Node\NameNode $type       Class of exception
     * @param string              $var        Variable for exception
     * @param \PHPParser\Node[]    $Statements      Statements
     * @param array               $attributes Additional attributes
     */
    public function __construct(\PHPParser\Node\NameNode $type, $var, array $Statements = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'type'  => $type,
                'var'   => $var,
                'Statements' => $Statements,
            ),
            $attributes
        );
    }
}