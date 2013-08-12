<?php

namespace PHPParserTest\Builder;

use PHPParser\Node\NameNode;

use PHPParser\Node\Scalar\StringScalar;

use PHPParser\Node\ParamNode;

use \PHPParser\Node\Name;
use \PHPParser\Node\Scalar\String;
use \PHPParser\Node\Expression\PrintExpression;
use \PHPParser\Node\Statement\FunctionStatement;
use \PHPParser\Node\Param;
use \PHPParser\Builder\FunctionBuilder;

class FunctionBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function createFunctionBuilder($name) {
        return new FunctionBuilder($name);
    }

    public function testReturnByRef() {
        $node = $this->createFunctionBuilder('test')
            ->makeReturnByRef()
            ->getNode()
        ;

        $this->assertEquals(
            new FunctionStatement('test', array(
                'byRef' => true
            )),
            $node
        );
    }

    public function testParams() {
        $param1 = new ParamNode('test1');
        $param2 = new ParamNode('test2');
        $param3 = new ParamNode('test3');

        $node = $this->createFunctionBuilder('test')
            ->addParam($param1)
            ->addParams(array($param2, $param3))
            ->getNode()
        ;

        $this->assertEquals(
            new FunctionStatement('test', array(
                'params' => array($param1, $param2, $param3)
            )),
            $node
        );
    }

    public function testStatements() {
        $Statement1 = new \PHPParser\Node\Expression\PrintExpression(new StringScalar('test1'));
        $Statement2 = new \PHPParser\Node\Expression\PrintExpression(new StringScalar('test2'));
        $Statement3 = new \PHPParser\Node\Expression\PrintExpression(new StringScalar('test3'));

        $node = $this->createFunctionBuilder('test')
            ->addStatement($Statement1)
            ->addStatements(array($Statement2, $Statement3))
            ->getNode()
        ;

        $this->assertEquals(
            new FunctionStatement('test', array(
                'Statements' => array($Statement1, $Statement2, $Statement3)
            )),
            $node
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Expected parameter node, got "PHPParser\Node\NameNode"
     */
    public function testInvalidParamError() {
        $this->createFunctionBuilder('test')
            ->addParam(new NameNode('foo'))
        ;
    }
}
