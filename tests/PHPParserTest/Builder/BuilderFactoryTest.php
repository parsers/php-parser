<?php

namespace PHPParserTest\Builder;

use \PHPParser\Builder\BuilderFactory;

class BuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideTestFactory
     */
    public function testFactory($methodName, $className) {
        $factory = new BuilderFactory();
        $this->assertInstanceOf($className, $factory->$methodName('test'));
    }

    public function provideTestFactory() {
        return array(
            array('class',     '\PHPParser\Builder\ClassBuilder'),
            array('interface', '\PHPParser\Builder\InterfaceBuilder'),
            array('method',    '\PHPParser\Builder\MethodBuilder'),
            array('function',  '\PHPParser\Builder\FunctionBuilder'),
            array('property',  '\PHPParser\Builder\PropertyBuilder'),
            array('param',     '\PHPParser\Builder\ParamBuilder'),
        );
    }
}
