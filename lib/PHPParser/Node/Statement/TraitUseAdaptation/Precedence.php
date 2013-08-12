<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Name   $trait     Trait name
 * @property string                $method    Method name
 * @property \PHPParser\Node\Name[] $insteadof Overwritten traits
 */
class TraitUseAdaptation_Precedence extends \PHPParser\Node\Statement\TraitUseAdaptation
{
    /**
     * Constructs a trait use precedence adaptation node.
     *
     * @param \PHPParser\Node\Name   $trait       Trait name
     * @param string                $method      Method name
     * @param \PHPParser\Node\Name[] $insteadof   Overwritten traits
     * @param array                 $attributes  Additional attributes
     */
    public function __construct(\PHPParser\Node\NameNode $trait, $method, array $insteadof, array $attributes = array()) {
        parent::__construct(
            array(
                'trait'     => $trait,
                'method'    => $method,
                'insteadof' => $insteadof,
            ),
            $attributes
        );
    }
}