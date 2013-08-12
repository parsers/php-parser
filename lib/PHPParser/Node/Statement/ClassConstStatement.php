<?php

namespace PHPParser\Node\Statement;



/**
 * @property \PHPParser\Node\Const[] $consts Constant declarations
 */
class ClassConstStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a class const list node.
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