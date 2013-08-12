<?php

namespace PHPParser\Node\Statement;

/**
 * @property null|\PHPParser\Node\NameNode $trait       Trait name
 * @property string                   $method      Method name
 * @property null|int                 $newModifier New modifier
 * @property null|string              $newName     New name
 */
class TraitUseAdaptation_Alias extends \PHPParser\Node\Statement\TraitUseAdaptation
{
    /**
     * Constructs a trait use precedence adaptation node.
     *
     * @param null|\PHPParser\Node\NameNode $trait       Trait name
     * @param string                   $method      Method name
     * @param null|int                 $newModifier New modifier
     * @param null|string              $newName     New name
     * @param array                    $attributes  Additional attributes
     */
    public function __construct($trait, $method, $newModifier, $newName, array $attributes = array()) {
        parent::__construct(
            array(
                'trait'       => $trait,
                'method'      => $method,
                'newModifier' => $newModifier,
                'newName'     => $newName,
            ),
            $attributes
        );
    }
}