<?php

namespace PHPParserTest\Node\Statement;

use \PHPParser\Node\Statement\TraitUseStatement;
use \PHPParser\Node\Statement\PropertyStatement;
use \PHPParser\Node\Statement\ConstStatement;
use \PHPParser\Node\Statement\ClassMethodStatement;
use \PHPParser\Node\Statement\ClassStatement;

class ClassTest extends \PHPUnit_Framework_TestCase
{
    public function testIsAbstract() {
        $class = new ClassStatement('Foo', array('type' => ClassStatement::MODIFIER_ABSTRACT));
        $this->assertTrue($class->isAbstract());

        $class = new ClassStatement('Foo');
        $this->assertFalse($class->isAbstract());
    }

    public function testIsFinal() {
        $class = new ClassStatement('Foo', array('type' => ClassStatement::MODIFIER_FINAL));
        $this->assertTrue($class->isFinal());

        $class = new ClassStatement('Foo');
        $this->assertFalse($class->isFinal());
    }

    public function testGetMethods() {
        $methods = array(
            new ClassMethodStatement('foo'),
            new ClassMethodStatement('bar'),
            new ClassMethodStatement('fooBar'),
        );
        $class = new ClassStatement('Foo', array(
            'Statements' => array(
                new TraitUseStatement(array()),
                $methods[0],
                new ConstStatement(array()),
                $methods[1],
                new PropertyStatement(0, array()),
                $methods[2],
            )
        ));

        $this->assertEquals($methods, $class->getMethods());
    }
}
