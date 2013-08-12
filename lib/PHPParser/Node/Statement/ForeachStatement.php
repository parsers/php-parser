<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Expr      $expr     Expression to iterate
 * @property null|\PHPParser\Node\Expression\Expression $keyVar   Variable to assign key to
 * @property bool                     $byRef    Whether to assign value by reference
 * @property \PHPParser\Node\Expr      $valueVar Variable to assign value to
 * @property \PHPParser\Node[]         $Statements    Statements
 */
class ForeachStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a foreach node.
     *
     * @param \PHPParser\Node\Expression\Expression $expr       Expression to iterate
     * @param \PHPParser\Node\Expression\Expression $valueVar   Variable to assign value to
     * @param array               $subNodes   Array of the following optional subnodes:
     *                                        'keyVar' => null   : Variable to assign key to
     *                                        'byRef'  => false  : Whether to assign value by reference
     *                                        'Statements'  => array(): Statements
     * @param array               $attributes Additional attributes
     */
    public function __construct(\PHPParser\Node\Expression\Expression $expr, \PHPParser\Node\Expression\Expression $valueVar, array $subNodes = array(), array $attributes = array()) {
        parent::__construct(
            $subNodes + array(
                'keyVar' => null,
                'byRef'  => false,
                'Statements'  => array(),
            ),
            $attributes
        );
        $this->expr     = $expr;
        $this->valueVar = $valueVar;
    }
}