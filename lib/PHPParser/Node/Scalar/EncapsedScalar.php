<?php

namespace PHPParser\Node\Scalar;

/**
 * @property array $parts Encaps list
 */
class EncapsedScalar extends Scalar
{
    /**
     * Constructs an encapsed string node.
     *
     * @param array $parts      Encaps list
     * @param array $attributes Additional attributes
     */
    public function __construct(array $parts = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'parts' => $parts
            ),
            $attributes
        );
    }
}
