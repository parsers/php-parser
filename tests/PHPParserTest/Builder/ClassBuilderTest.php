<?php

namespace PHPParserTest\Builder;

use PHPParser\Node\Statement\TraitUseStatement;

use PHPParser\Node\Scalar\StringScalar;

use PHPParser\Node\ConstNode;

use PHPParser\Node\Statement\PropertyPropertyStatement;

use PHPParser\Node\NameNode;

use \PHPParser\Node\Scalar\String;
use \PHPParser\Node\Statement\EchoStatement;
use \PHPParser\Node\Statement\TraitUse;
use \PHPParser\Node\Node_Const;
use \PHPParser\Node\Scalar\ClassConstStatement;
use \PHPParser\Node\Statement\PropertyStatement;
use \PHPParser\Node\Statement\ClassMethodStatement;
use \PHPParser\Node\Statement\ClassStatement;
use \PHPParser\Node\Name;
use \PHPParser\Builder\ClassBuilder;

class ClassBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected function createClassBuilder($class) {
        return new ClassBuilder($class);
    }

    public function testExtendsImplements() {
        $node = $this->createClassBuilder('SomeLogger')
            ->extend('BaseLogger')
            ->implement('Namespaced\Logger', new NameNode('SomeInterface'))
            ->getNode()
        ;

        $this->assertEquals(
            new ClassStatement('SomeLogger', array(
                'extends' => new NameNode('BaseLogger'),
                'implements' => array(
                    new NameNode('Namespaced\Logger'),
                    new NameNode('SomeInterface')
                ),
            )),
            $node
        );
    }

    public function testAbstract() {
        $node = $this->createClassBuilder('Test')
            ->makeAbstract(-1)
            ->getNode()
        ;

        $this->assertEquals(
            new ClassStatement('Test', array(
                'type' => ClassStatement::MODIFIER_ABSTRACT
            )),
            $node
        );
    }

    public function testFinal() {
        $node = $this->createClassBuilder('Test')
            ->makeFinal(-1)
            ->getNode()
        ;

        $this->assertEquals(
            new ClassStatement('Test', array(
                'type' => ClassStatement::MODIFIER_FINAL
            )),
            $node
        );
    }

    public function testStatementOrder() {
        $method = new ClassMethodStatement('testMethod');
        $property = new PropertyStatement(
            ClassStatement::MODIFIER_PUBLIC,
            array(new PropertyPropertyStatement('testPropertyStatement'))
        );
        $const = new \PHPParser\Node\Statement\ClassConstStatement(array(
            new ConstNode('TEST_CONST', new StringScalar('ABC'))
        ));
        $use = new TraitUseStatement(array(new NameNode('SomeTrait')));

        $node = $this->createClassBuilder('Test')
            ->addStatement($method)
            ->addStatement($property)
            ->addStatements(array($const, $use))
            ->getNode()
        ;

        $this->assertEquals(
            new ClassStatement('Test', array(
                'Statements' => array($use, $const, $property, $method)
            )),
            $node
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Unexpected node of type "PHPParser\Node\Statement\EchoStatement"
     */
    public function testInvalidStatementError() {
        $this->createClassBuilder('Test')
            ->addStatement(new EchoStatement(array()));
    }
}
