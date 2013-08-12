<?php

namespace PHPParserTest\Node\Statement;

use \PHPParser\Node\Statement\PropertyStatement;

class PropertyStatementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideModifiers
     */
    public function testModifiers($modifier) {
        $node = new PropertyStatement(
            constant('\PHPParser\Node\Statement\ClassStatement::MODIFIER_' . strtoupper($modifier)),
            array() // invalid
        );

        $this->assertTrue($node->{'is' . $modifier}());
    }

    /**
     * @dataProvider provideModifiers
     */
    public function testNoModifiers($modifier) {
        $node = new PropertyStatement(0, array());

        $this->assertFalse($node->{'is' . $modifier}());
    }

    public function provideModifiers() {
        return array(
            array('public'),
            array('protected'),
            array('private'),
            array('static'),
        );
    }
}
