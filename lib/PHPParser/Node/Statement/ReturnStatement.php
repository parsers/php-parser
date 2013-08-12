<?php

namespace PHPParser\Node\Statement;

/**
 * @property null|\PHPParser\Node\Expression\Expression $expr Expression
 */
class ReturnStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a return node.
     *
     * @param null|\PHPParser\Node\Expression\Expression $expr       Expression
     * @param array                    $attributes Additional attributes
     */
    public function __construct(\PHPParser\Node\Expression\Expression $expr = null, array $attributes = array()) {
        parent::__construct(
            array(
                'expr' => $expr,
            ),
            $attributes
        );
    }
}