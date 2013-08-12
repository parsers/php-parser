<?php

namespace PHPParser\Builder;

use \PHPParser\Node\Statement\FunctionStatement;

class FunctionBuilder extends BuilderAbstract
{
    protected $name;

    protected $returnByRef;
    protected $params;
    protected $Statements;

    /**
     * Creates a function builder.
     *
     * @param string $name Name of the function
     */
    public function __construct($name) {
        $this->name = $name;

        $this->returnByRef = false;
        $this->params = array();
        $this->Statements = array();
    }

    /**
     * Make the function return by reference.
     *
     * @return \PHPParser\FunctionBuilder The builder instance (for fluid interface)
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
     * @return \PHPParser\FunctionBuilder The builder instance (for fluid interface)
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
     * @return \PHPParser\FunctionBuilder The builder instance (for fluid interface)
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
     * @return \PHPParser\FunctionBuilder The builder instance (for fluid interface)
     */
    public function addStatement($Statement) {
        $this->Statements[] = $this->normalizeNode($Statement);

        return $this;
    }

    /**
     * Adds multiple statements.
     *
     * @param array $Statements The statements to add
     *
     * @return \PHPParser\FunctionBuilder The builder instance (for fluid interface)
     */
    public function addStatements(array $Statements) {
        foreach ($Statements as $Statement) {
            $this->addStatement($Statement);
        }

        return $this;
    }

    /**
     * Returns the built function node.
     *
     * @return \PHPParser\Node\Statement\FunctionStatement The built function node
     */
    public function getNode() {
        return new FunctionStatement($this->name, array(
            'byRef'  => $this->returnByRef,
            'params' => $this->params,
            'Statements'  => $this->Statements,
        ));
    }
}
