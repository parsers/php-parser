<?php

namespace PHPParser\Builder;

use PHPParser\Node\Statement\PropertyPropertyStatement;

use \PHPParser\Node\Statement\PropertyStatement;
use \PHPParser\Node\Statement\ClassStatement;

class PropertyBuilder extends BuilderAbstract
{
    protected $name;

    protected $type;
    protected $default;

    /**
     * Creates a property builder.
     *
     * @param string $name Name of the property
     */
    public function __construct($name) {
        $this->name = $name;

        $this->type = 0;
        $this->default = null;
    }

    /**
     * Makes the property public.
     *
     * @return \PHPParser\PropertyBuilder The builder instance (for fluid interface)
     */
    public function makePublic($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_PUBLIC, $startLine);

        return $this;
    }

    /**
     * Makes the property protected.
     *
     * @return \PHPParser\PropertyBuilder The builder instance (for fluid interface)
     */
    public function makeProtected($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_PROTECTED, $startLine);

        return $this;
    }

    /**
     * Makes the property private.
     *
     * @return \PHPParser\Builder\PropertyBuilder The builder instance (for fluid interface)
     */
    public function makePrivate($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_PRIVATE, $startLine);

        return $this;
    }

    /**
     * Makes the property static.
     *
     * @return \PHPParser\Builder\PropertyBuilder The builder instance (for fluid interface)
     */
    public function makeStatic($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_STATIC, $startLine);

        return $this;
    }

    /**
     * Sets default value for the property.
     *
     * @param mixed $value Default value to use
     *
     * @return \PHPParser\Builder\PropertyBuilder The builder instance (for fluid interface)
     */
    public function setDefault($value) {
        $this->default = $this->normalizeValue($value);

        return $this;
    }

    /**
     * Returns the built class node.
     *
     * @return \PHPParser\Node\Statement\PropertyStatement The built property node
     */
    public function getNode() {
        return new PropertyStatement(
            $this->type !== 0 ? $this->type : ClassStatement::MODIFIER_PUBLIC,
            array(
                new PropertyPropertyStatement($this->name, $this->default)
            )
        );
    }
}
