<?php

namespace PHPParser\Node\Statement;

/**
 * @property int                      $type       Type
 * @property string                   $name       Name
 * @property null|\PHPParser\Node\NameNode $extends    Name of extended class
 * @property \PHPParser\Node\Name[]    $implements Names of implemented interfaces
 * @property \PHPParser\Node[]         $Statements      Statements
 */
class ClassStatement extends \PHPParser\Node\Statement\Statement
{
    const MODIFIER_PUBLIC    =  1;
    const MODIFIER_PROTECTED =  2;
    const MODIFIER_PRIVATE   =  4;
    const MODIFIER_STATIC    =  8;
    const MODIFIER_ABSTRACT  = 16;
    const MODIFIER_FINAL     = 32;

    protected static $specialNames = array(
        'self'   => true,
        'parent' => true,
        'static' => true,
    );

    /**
     * Constructs a class node.
     *
     * @param string      $name       Name
     * @param array       $subNodes   Array of the following optional subnodes:
     *                                'type'       => 0      : Type
     *                                'extends'    => null   : Name of extended class
     *                                'implements' => array(): Names of implemented interfaces
     *                                'Statements'      => array(): Statements
     * @param array       $attributes Additional attributes
     */
    public function __construct($name, array $subNodes = array(), array $attributes = array()) {
        parent::__construct(
            $subNodes + array(
                'type'       => 0,
                'extends'    => null,
                'implements' => array(),
                'Statements'      => array(),
            ),
            $attributes
        );
        $this->name = $name;

        if (isset(self::$specialNames[(string) $this->name])) {
            throw new \PHPParser\Error\Error(sprintf('Cannot use "%s" as class name as it is reserved', $this->name), $attributes['startLine']);
        }

        if (isset(self::$specialNames[(string) $this->extends])) {
            throw new \PHPParser\Error\Error(sprintf('Cannot use "%s" as class name as it is reserved', $this->extends), $attributes['startLine']);
        }

        foreach ($this->implements as $interface) {
            if (isset(self::$specialNames[(string) $interface])) {
                throw new \PHPParser\Error\Error(sprintf('Cannot use "%s" as interface name as it is reserved', $interface), $attributes['startLine']);
            }
        }
    }

    public function isAbstract() {
        return (bool) ($this->type & self::MODIFIER_ABSTRACT);
    }

    public function isFinal() {
        return (bool) ($this->type & self::MODIFIER_FINAL);
    }

    public function getMethods() {
        $methods = array();
        foreach ($this->Statements as $Statement) {
            if ($Statement instanceof \PHPParser\Node\Statement\ClassMethodStatement) {
                $methods[] = $Statement;
            }
        }
        return $methods;
    }

    public static function verifyModifier($a, $b, $line) {
        if ($a & 7 && $b & 7) {
            throw new \PHPParser\Error\Error('Multiple access type modifiers are not allowed', $line);
        }

        if ($a & self::MODIFIER_ABSTRACT && $b & self::MODIFIER_ABSTRACT) {
            throw new \PHPParser\Error\Error('Multiple abstract modifiers are not allowed', $line);
        }

        if ($a & self::MODIFIER_STATIC && $b & self::MODIFIER_STATIC) {
            throw new \PHPParser\Error\Error('Multiple static modifiers are not allowed', $line);
        }

        if ($a & self::MODIFIER_FINAL && $b & self::MODIFIER_FINAL) {
            throw new \PHPParser\Error\Error('Multiple final modifiers are not allowed', $line);
        }

        if ($a & 48 && $b & 48) {
            throw new \PHPParser\Error\Error('Cannot use the final and abstract modifier at the same time', $line);
        }
    }
}