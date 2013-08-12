<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Name[]                    $traits      Traits
 * @property \PHPParser\Node\Statement\TraitStatementUseAdaptation[] $adaptations Adaptations
 */


class TraitUseStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a trait use node.
     *
     * @param \PHPParser\Node\Name[]                    $traits      Traits
     * @param \PHPParser\Node\Statement\TraitStatementUseAdaptation[] $adaptations Adaptations
     * @param array                                    $attributes  Additional attributes
     */
    public function __construct(array $traits, array $adaptations = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'traits'      => $traits,
                'adaptations' => $adaptations,
            ),
            $attributes
        );
    }
}