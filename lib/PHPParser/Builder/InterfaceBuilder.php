<?php

namespace PHPParser\Builder;

use \PHPParser\Node\Statement\InterfaceStatement;
use \PHPParser\Builder\BuilderAbstract;

class InterfaceBuilder extends BuilderAbstract
{
    protected $name;
    protected $extends;
    protected $constants;
    protected $methods;

    /**
     * Creates an interface builder.
     *
     * @param string $name Name of the interface
     */
    public function __construct($name) {
        $this->name = $name;
        $this->extends = array();
        $this->constants = $this->methods = array();
    }

    /**
     * Extends one or more interfaces.
     *
     * @param \PHPParser\Node\Name|string $interface Name of interface to extend
     * @param \PHPParser\Node\Name|string $...       More interfaces to extend
     *
     * @return \PHPParser\InterfaceBuilder The builder instance (for fluid interface)
     */
    public function extend() {
        foreach (func_get_args() as $interface) {
            $this->extends[] = $this->normalizeName($interface);
        }

        return $this;
    }

    /**
     * Adds a statement.
     *
     * @param \PHPParser\Node\Statement|\PHPParser\Builder $Statement The statement to add
     *
     * @return \PHPParser\InterfaceBuilder The builder instance (for fluid interface)
     */
    public function addStatement($Statement) {
        $Statement = $this->normalizeNode($Statement);

        $type = $Statement->getType();
        switch ($type) {
            case 'PHPParser\Node\Statement\ClassConstStatement':
                $this->constants[] = $Statement;
                break;

            case 'PHPParser\Node\Statement\ClassMethodStatement':
                // we erase all statements in the body of an interface method
                $Statement->Statements = null;
                $this->methods[] = $Statement;
                break;

            default:
                throw new \LogicException(sprintf('Unexpected node of type "%s"', $type));
        }

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
     * @return \PHPParser\Node\Statement\InterfaceStatement The built interface node
     */
    public function getNode() {
        return new InterfaceStatement($this->name, array(
            'extends' => $this->extends,
            'Statements' => array_merge($this->constants, $this->methods),
        ));
    }
}