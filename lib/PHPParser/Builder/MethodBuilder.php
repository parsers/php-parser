<?php

namespace PHPParser\Builder;

use \PHPParser\Node\Statement\ClassStatement;
use \PHPParser\Node\Statement\ClassMethodStatement;

class MethodBuilder extends BuilderAbstract
{
    protected $name;

    protected $type;
    protected $returnByRef;
    protected $params;
    protected $Statements;

    /**
     * Creates a method builder.
     *
     * @param string $name Name of the method
     */
    public function __construct($name) {
        $this->name = $name;

        $this->type = 0;
        $this->returnByRef = false;
        $this->params = array();
        $this->Statements = array();
    }

    /**
     * Makes the method public.
     *
     * @return \PHPParser\Builder\MethodBuilder The builder instance (for fluid interface)
     */
    public function makePublic($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_PUBLIC, $startLine);

        return $this;
    }

    /**
     * Makes the method protected.
     *
     * @return \PHPParser\Builder\MethodBuilder The builder instance (for fluid interface)
     */
    public function makeProtected($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_PROTECTED, $startLine);

        return $this;
    }

    /**
     * Makes the method private.
     *
     * @return \PHPParser\Builder\MethodBuilder The builder instance (for fluid interface)
     */
    public function makePrivate($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_PRIVATE, $startLine);

        return $this;
    }

    /**
     * Makes the method static.
     *
     * @return \PHPParser\Builder\MethodBuilder The builder instance (for fluid interface)
     */
    public function makeStatic($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_STATIC, $startLine);

        return $this;
    }

    /**
     * Makes the method abstract.
     *
     * @return \PHPParser\Builder\MethodBuilder The builder instance (for fluid interface)
     */
    public function makeAbstract($startLine) {
        if (!empty($this->Statements)) {
            throw new \LogicException('Cannot make method with statements abstract');
        }

        $this->setModifier(ClassStatement::MODIFIER_ABSTRACT, $startLine);
        $this->Statements = null; // abstract methods don't have statements

        return $this;
    }

    /**
     * Makes the method final.
     *
     * @return \PHPParser\Builder\MethodBuilder The builder instance (for fluid interface)
     */
    public function makeFinal($startLine) {
        $this->setModifier(ClassStatement::MODIFIER_FINAL, $startLine);

        return $this;
    }

    /**
     * Make the method return by reference.
     *
     * @return \PHPParser\MethodBuilder The builder instance (for fluid interface)
     */
    public function makeReturnByRef() {
        $this->returnByRef = true;

        return $this;
    }

    /**
     * Adds a parameter.
     *
     * @param \PHPParser\Node\Param|\PHPParser\ParamBuilder $param The parameter to add
     *
     * @return \PHPParser\MethodBuilder The builder instance (for fluid interface)
     */
    public function addParam($param) {
        $param = $this->normalizeNode($param);

        if (!$param instanceof \PHPParser\Node\ParamNode) {
            throw new \LogicException(sprintf('Expected parameter node, got "%s"', $param->getType()));
        }

        $this->params[] = $param;

        return $this;
    }

    /**
     * Adds multiple parameters.
     *
     * @param array $params The parameters to add
     *
     * @return \PHPParser\MethodBuilder The builder instance (for fluid interface)
     */
    public function addParams(array $params) {
        foreach ($params as $param) {
            $this->addParam($param);
        }

        return $this;
    }

    /**
     * Adds a statement.
     *
     * @param \PHPParser\Node|\PHPParser\Builder $Statement The statement to add
     *
     * @return \PHPParser\MethodBuilder The builder instance (for fluid interface)
     */
    public function addStatement($Statement) {
        if (null === $this->Statements) {
            throw new \LogicException('Cannot add statements to an abstract method');
        }

        $this->Statements[] = $this->normalizeNode($Statement);

        return $this;
    }

    /**
     * Adds multiple statements.
     *
     * @param array $Statements The statements to add
     *
     * @return \PHPParser\MethodBuilder The builder instance (for fluid interface)
     */
    public function addStatements(array $Statements) {
        foreach ($Statements as $Statement) {
            $this->addStatement($Statement);
        }

        return $this;
    }

    /**
     * Returns the built method node.
     *
     * @return \PHPParser\Node\Statement\ClassStatementMethod The built method node
     */
    public function getNode() {
        return new ClassMethodStatement($this->name, array(
            'type'   => $this->type !== 0 ? $this->type : ClassStatement::MODIFIER_PUBLIC,
            'byRef'  => $this->returnByRef,
            'params' => $this->params,
            'Statements'  => $this->Statements,
        ));
    }
}
