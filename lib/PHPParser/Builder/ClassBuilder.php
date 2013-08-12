<?php

namespace PHPParser\Builder;

use \PHPParser\Node\Statement\ClassStatement;

class ClassBuilder extends BuilderAbstract
{
	/**
	 * Class name
	 * @var string
	 */
    protected $name;

    protected $extends;
    protected $implements;
    protected $type;

    protected $uses;
    protected $constants;
    protected $properties;
    protected $methods;

    /**
     * Creates a class builder.
     *
     * @param string $name Name of the class
     */
    public function __construct($name) {
        $this->name = $name;

        $this->type = 0;
        $this->extends = null;
        $this->implements = array();

        $this->uses = $this->constants = $this->properties = $this->methods = array();
    }

    /**
     * Extends a class.
     *
     * @param \PHPParser\Node\Name|string $class Name of class to extend
     *
     * @return \PHPParser\Builder\ClassBuilder The builder instance (for fluid interface)
     */
    public function extend($class) {
        $this->extends = $this->normalizeName($class);

        return $this;
    }

    /**
     * Implements one or more interfaces.
     *
     * @param \PHPParser\Node\Name|string $interface Name of interface to implement
     * @param \PHPParser\Node\Name|string $...       More interfaces to implement
     *
     * @return \PHPParser\ClassBuilder The builder instance (for fluid interface)
     */
    public function implement() {
        foreach (func_get_args() as $interface) {
            $this->implements[] = $this->normalizeName($interface);
        }

        return $this;
    }

    /**
     * Makes the class abstract.
     *
     * @return \PHPParser\ClassBuilder The builder instance (for fluid interface)
     */
    public function makeAbstract($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_ABSTRACT, $startLine);

        return $this;
    }

    /**
     * Makes the class final.
     *
     * @return \PHPParser\ClassBuilder The builder instance (for fluid interface)
     */
    public function makeFinal($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_FINAL, $startLine);

        return $this;
    }

    /**
     * Adds a statement.
     *
     * @param \PHPParser\Node\Statement|\PHPParser\Builder $Statement The statement to add
     *
     * @return \PHPParser\ClassBuilder The builder instance (for fluid interface)
     */
    public function addStatement($Statement) {
        $Statement = $this->normalizeNode($Statement);

        $targets = array(
            'PHPParser\Node\Statement\TraitUseStatement'    => &$this->uses,
            'PHPParser\Node\Statement\ClassConstStatement'  => &$this->constants,
            'PHPParser\Node\Statement\PropertyStatement'    => &$this->properties,
            'PHPParser\Node\Statement\ClassMethodStatement' => &$this->methods,
        );

        $type = $Statement->getType();
        if (!isset($targets[$type])) {
            throw new \LogicException(sprintf('Unexpected node of type "%s"', $type));
        }

        $targets[$type][] = $Statement;

        return $this;
    }

    /**
     * Adds multiple statements.
     *
     * @param array $Statements The statements to add
     *
     * @return \PHPParser\ClassBuilder The builder instance (for fluid interface)
     */
    public function addStatements(array $Statements) {
        foreach ($Statements as $Statement) {
            $this->addStatement($Statement);
        }

        return $this;
    }

    /**
     * Returns the built class node.
     *
     * @return \PHPParser\Node\Statement\ClassStatement The built class node
     */
    public function getNode() {
        return new ClassStatement($this->name, array(
            'type' => $this->type,
            'extends' => $this->extends,
            'implements' => $this->implements,
            'Statements' => array_merge($this->uses, $this->constants, $this->properties, $this->methods),
        ));
    }
}
