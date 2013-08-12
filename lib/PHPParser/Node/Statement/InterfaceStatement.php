<?php

namespace PHPParser\Node\Statement;

use \PHPParser\Error\Error;

/**
 * @property string                $name    Name
 * @property \PHPParser\Node\Name[] $extends Extended interfaces
 * @property \PHPParser\Node[]      $Statements   Statements
 */
class InterfaceStatement extends \PHPParser\Node\Statement\Statement
{
    protected static $specialNames = array(
        'self'   => true,
        'parent' => true,
        'static' => true,
    );

    /**
     * Constructs a class node.
     *
     * @param string $name       Name
     * @param array  $subNodes   Array of the following optional subnodes:
     *                           'extends' => array(): Name of extended interfaces
     *                           'Statements'   => array(): Statements
     * @param array  $attributes Additional attributes
     */
    public function __construct($name, array $subNodes = array(), array $attributes = array()) {
        parent::__construct(
            $subNodes + array(
                'extends' => array(),
                'Statements'   => array(),
            ),
            $attributes
        );
        $this->name = $name;

        if (isset(self::$specialNames[(string) $this->name])) {
            throw new Error(sprintf('Cannot use "%s" as interface name as it is reserved', $this->name), $attributes['startLine']);
        }

        foreach ($this->extends as $interface) {
            if (isset(self::$specialNames[(string) $interface])) {
                throw new Error(sprintf('Cannot use "%s" as interface name as it is reserved', $interface), $attributes['startLine']);
            }
        }
    }
}