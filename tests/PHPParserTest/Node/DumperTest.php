<?php

namespace PHPParserTest\Node;

use PHPParser\Node\NameNode;

use \PHPParser\Node\Scalar\StringScalar;
use \PHPParser\Node\Dumper;
use \PHPParser\Node\Expression\ArrayItemExpression;
use \PHPParser\Node\Expression\ArrayExpression;
use \PHPParser\Node\Name;

class DumperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideTestDump
     * @covers \PHPParser\NodeDumper::dump
     */
    public function testDump($node, $dump) {
        $dumper = new Dumper;

        $this->assertEquals($dump, $dumper->dump($node));
    }

    public function provideTestDump() {
        return array(
            array(
                array(),
'array(
)'
            ),
            array(
                array('Foo', 'Bar', 'Key' => 'FooBar'),
'array(
    0: Foo
    1: Bar
    Key: FooBar
)'
            ),
            array(
                new NameNode(array('Hallo', 'World')),
'PHPParser\Node\NameNode(
    parts: array(
        0: Hallo
        1: World
    )
)'
            ),
            array(
                new ArrayExpression(array(
                    new ArrayItemExpression(new StringScalar('Foo'))
                )),
'PHPParser\Node\Expression\ArrayExpression(
    items: array(
        0: PHPParser\Node\Expression\ArrayItemExpression(
            key: null
            value: PHPParser\Node\Scalar\StringScalar(
                value: Foo
            )
            byRef: false
        )
    )
)'
            ),
        );
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Can only dump nodes and arrays.
     */
    public function testError() {
        $dumper = new Dumper;
        $dumper->dump(new \stdClass);
    }
}
