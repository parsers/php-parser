<?php

namespace PHPParser\Node\Statement;

/**
 * @property \PHPParser\Node\NameNode $name  Namespace/Class to alias
 * @property string              $alias Alias
 */
class UseUseStatement extends \PHPParser\Node\Statement\Statement
{
    /**
     * Constructs an alias (use) node.
     *
     * @param \PHPParser\Node\NameNode $name       Namespace/Class to alias
     * @param null|string         $alias      Alias
     * @param array               $attributes Additional attributes
     */
    public function __construct(\PHPParser\Node\NameNode $name, $alias = null, array $attributes = array()) {
        if (null === $alias) {
            $alias = $name->getLast();
        }

        if ('self' == $alias || 'parent' == $alias) {
            throw new \PHPParser\Error\Error(sprintf(
                'Cannot use "%s" as "%s" because "%2$s" is a special class name',
                $name, $alias
            ), $attributes['startLine']);
        }

        parent::__construct(
            array(
                'name'  => $name,
                'alias' => $alias,
            ),
            $attributes
        );
    }
}