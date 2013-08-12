<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Const[] $consts Constant declarations
 */
class ConstStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a const list node.
     *
     * @param \PHPParser\Node\Const[] $consts     Constant declarations
     * @param array                  $attributes Additional attributes
     */
    public function __construct(array $consts, array $attributes = array()) {
        parent::__construct(
            array(
                'consts' => $consts,
            ),
            $attributes
        );
    }
}