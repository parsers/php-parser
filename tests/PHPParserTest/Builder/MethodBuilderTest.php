<?php

namespace PHPParserTest\Builder;

use PHPParser\Node\NameNode;

use PHPParser\Node\Scalar\StringScalar;

use PHPParser\Node\ParamNode;

use \PHPParser\Node\Name;

use \PHPParser\Node\Scalar\String;

use \PHPParser\Node\Expression\PrintExpression;

use \PHPParser\Node\Param;

use \PHPParser\Node\Statement\ClassStatement;

use \PHPParser\Builder\MethodBuilder;
use \PHPParser\Node\Statement\ClassMethodStatement;

class MethodBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function createMethodBuilder($name) {
        return new MethodBuilder($name);
    }

    public function testModifiers() {
        $node = $this->createMethodBuilder('test')
            ->makePublic(-1)
            ->makeAbstract(-1)
            ->makeStatic(-1)
            ->getNode()
        ;

        $this->assertEquals(
            new ClassMethodStatement('test', array(
                'type' => ClassStatement::MODIFIER_PUBLIC
                        | ClassStatement::MODIFIER_ABSTRACT
                        | ClassStatement::MODIFIER_STATIC,
                'Statements' => null,
            )),
            $node
        );

        $node = $this->createMethodBuilder('test')
            ->makeProtected(-1)
            ->makeFinal(-1)
            ->getNode()
        ;

        $this->assertEquals(
            new ClassMethodStatement('test', array(
                'type' => ClassStatement::MODIFIER_PROTECTED
                        | ClassStatement::MODIFIER_FINAL
            )),
            $node
        );

        $node = $this->createMethodBuilder('test')
            ->makePrivate(-1)
            ->getNode()
        ;

        $this->assertEquals(
            new ClassMethodStatement('test', array(
                'type' => ClassStatement::MODIFIER_PRIVATE
            )),
            $node
        );
    }

    public function testReturnByRef() {
        $node = $this->createMethodBuilder('test')
            ->makeReturnByRef()
            ->getNode()
        ;

        $this->assertEquals(
            new ClassMethodStatement('test', array(
                'byRef' => true
            )),
            $node
        );
    }

    public function testParams() {
        $param1 = new ParamNode('test1');
        $param2 = new ParamNode('test2');
        $param3 = new ParamNode('test3');

        $node = $this->createMethodBuilder('test')
            ->addParam($param1)
            ->addParams(array($param2, $param3))
            ->getNode()
        ;

        $this->assertEquals(
            new ClassMethodStatement('test', array(
                'params' => array($param1, $param2, $param3)
            )),
            $node
        );
    }

    public function testStatements() {
        $Statement1 = new \PHPParser\Node\Expression\PrintExpression(new StringScalar('test1'));
        $Statement2 = new \PHPParser\Node\Expression\PrintExpression(new StringScalar('test2'));
        $Statement3 = new \PHPParser\Node\Expression\PrintExpression(new StringScalar('test3'));

        $node = $this->createMethodBuilder('test')
            ->addStatement($Statement1)
            ->addStatements(array($Statement2, $Statement3))
            ->getNode()
        ;

        $this->assertEquals(
            new ClassMethodStatement('test', array(
                'Statements' => array($Statement1, $Statement2, $Statement3)
            )),
            $node
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Cannot add statements to an abstract method
     */
    public function testAddStatementToAbstractMethodError() {
        $this->createMethodBuilder('test')
            ->makeAbstract(-1)
            ->addStatement(new \PHPParser\Node\Expression\PrintExpression(new StringScalar('test')))
        ;
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Cannot make method with statements abstract
     */
    public function testMakeMethodWithStatementsAbstractError() {
        $this->createMethodBuilder('test')
            ->addStatement(new \PHPParser\Node\Expression\PrintExpression(new StringScalar('test')))
            ->makeAbstract(-1)
        ;
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Expected parameter node, got "PHPParser\Node\NameNode"
     */
    public function testInvalidParamError() {
        $this->createMethodBuilder('test')
            ->addParam(new NameNode('foo'))
        ;
    }
}
