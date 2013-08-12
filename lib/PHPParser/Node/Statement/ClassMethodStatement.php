<?php

namespace PHPParser\Node\Statement;

/**
 * @property int                    $type   Type
 * @property bool                   $byRef  Whether to return by reference
 * @property string                 $name   Name
 * @property \PHPParser\Node\Param[] $params Parameters
 * @property \PHPParser\Node[]       $Statements  Statements
 */
use PHPParser\Error\Error;

use \PHPParser\Node\Statement\ClassStatement;



class ClassMethodStatement extends \PHPParser\Node\Statement\Statement
{

    /**
     * Constructs a class method node.
     *
     * @param string      $name       Name
     * @param array       $subNodes   Array of the following optional subnodes:
     *                                'type'   => MODIFIER_PUBLIC: Type
     *                                'byRef'  => false          : Whether to return by reference
     *                                'params' => array()        : Parameters
     *                                'Statements'  => array()        : Statements
     * @param array       $attributes Additional attributes
     */
    public function __construct($name, array $subNodes = array(), array $attributes = array()) {
        parent::__construct(
            $subNodes + array(
                'type'   => ClassStatement::MODIFIER_PUBLIC,
                'byRef'  => false,
                'params' => array(),
                'Statements'  => array(),
            ),
            $attributes
        );
        $this->name = $name;

        if (($this->type & ClassStatement::MODIFIER_STATIC)
            && ('__construct' == $this->name || '__destruct' == $this->name || '__clone' == $this->name)
        ) {
            throw new Error(sprintf('"%s" method cannot be static', $this->name), $attributes['startLine']);
        }
    }

    public function isPublic() {
        return (bool) ($this->type & ClassStatement::MODIFIER_PUBLIC);
    }

    public function isProtected() {
        return (bool) ($this->type & ClassStatement::MODIFIER_PROTECTED);
    }

    public function isPrivate() {
        return (bool) ($this->type & ClassStatement::MODIFIER_PRIVATE);
    }

    public function isAbstract() {
        return (bool) ($this->type & ClassStatement::MODIFIER_ABSTRACT);
    }

    public function isFinal() {
        return (bool) ($this->type & ClassStatement::MODIFIER_FINAL);
    }

    public function isStatic() {
        return (bool) ($this->type & ClassStatement::MODIFIER_STATIC);
    }
}