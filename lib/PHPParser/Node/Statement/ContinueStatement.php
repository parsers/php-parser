<?php

namespace PHPParser\Node\Statement;

/**
 * @property null|\PHPParser\Node\Expression\Expression $num Number of loops to continue
 */
class ContinueStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs a continue node.
     *
     * @param null|\PHPParser\Node\Expression\Expression $num        Number of loops to continue
     * @param array                    $attributes Additional attributes
     */
    public function __construct(\PHPParser\Node\Expression\Expression $num = null, array $attributes = array()) {
        parent::__construct(
            array(
                'num' => $num,
            ),
            $attributes
        );
    }
}