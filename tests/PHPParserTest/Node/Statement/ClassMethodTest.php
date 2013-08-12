<?php

namespace PHPParserTest\Node\Statement;

use \PHPParser\Node\Statement\ClassMethodStatement;

class ClassMethodStatementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideModifiers
     */
    public function testModifiers($modifier) {
        $node = new ClassMethodStatement('foo', array(
            'type' => constant('PHPParser\Node\Statement\ClassStatement::MODIFIER_' . strtoupper($modifier))
        ));

        $this->assertTrue($node->{'is' . $modifier}());
    }

    /**
     * @dataProvider provideModifiers
     */
    public function testNoModifiers($modifier) {
        $node = new ClassMethodStatement('foo', array('type' => 0));

        $this->assertFalse($node->{'is' . $modifier}());
    }

    public function provideModifiers() {
        return array(
            array('public'),
            array('protected'),
            array('private'),
            array('abstract'),
            array('final'),
            array('static'),
        );
    }
}
