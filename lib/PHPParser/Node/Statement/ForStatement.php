<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Expr[] $init  Init expressions
 * @property \PHPParser\Node\Expr[] $cond  Loop conditions
 * @property \PHPParser\Node\Expr[] $loop  Loop expressions
 * @property \PHPParser\Node[]      $Statements Statements
 */
class ForStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a for loop node.
     *
     * @param array $subNodes   Array of the following optional subnodes:
     *                          'init'  => array(): Init expressions
     *                          'cond'  => array(): Loop conditions
     *                          'loop'  => array(): Loop expressions
     *                          'Statements' => array(): Statements
     * @param array $attributes Additional attributes
     */
    public function __construct(array $subNodes = array(), array $attributes = array()) {
        parent::__construct(
            $subNodes + array(
                'init'  => array(),
                'cond'  => array(),
                'loop'  => array(),
                'Statements' => array(),
            ),
            $attributes
        );
    }
}