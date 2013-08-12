<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\Statement\UseStatementUse[] $uses Aliases
 */
class UseStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs an alias (use) list node.
     *
     * @param \PHPParser\Node\Statement\UseStatementUse[] $uses       Aliases
     * @param array                        $attributes Additional attributes
     */
    public function __construct(array $uses, array $attributes = array()) {
        parent::__construct(
            array(
                'uses' => $uses,
            ),
            $attributes
        );
    }
}