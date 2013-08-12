<?php

namespace PHPParser\Node\Expression;

/**
 * @property \PHPParser\Node[]                 $Statements  Statements
 * @property \PHPParser\Node\Param[]           $params Parameters
 * @property ClosureUse[] $uses   use()s
 * @property bool                             $byRef  Whether to return by reference
 * @property bool                             $static Whether the closure is static
 */
class ClosureExpression extends Expression
{
    /**
     * Constructs a lambda function node.
     *
     * @param array $subNodes   Array of the following optional subnodes:
     *                          'Statements'  => array(): Statements
     *                          'params' => array(): Parameters
     *                          'uses'   => array(): use()s
     *                          'byRef'  => false  : Whether to return by reference
     *                          'static' => false  : Whether the closure is static
     * @param array $attributes Additional attributes
     */
    public function __construct(array $subNodes = array(), array $attributes = array()) {
        parent::__construct(
            $subNodes + array(
                'Statements'  => array(),
                'params' => array(),
                'uses'   => array(),
                'byRef'  => false,
                'static' => false,
            ),
            $attributes
        );
    }
}
