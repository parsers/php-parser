<?php

namespace PHPParserTest;

use \PHPParser\Template\Template;

use \PHPParser\PrettyPrinter\PrettyPrinterDefault;

use \PHPParser\Lexer\Lexer;

use \PHPParser\Parser\Parser;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
	
	public function testPlaceholderReplacement1() {
		
		list($templateCode, $placeholders, $expectedPrettyPrint) = 
				array(
                '<?php $__name__ + $__Name__;',
                array('name' => 'foo'),
                '$foo + $Foo;'
            )
				;
		
		$parser = new Parser(new Lexer);
		$prettyPrinter = new PrettyPrinterDefault;
	
		$template = new Template($parser, $templateCode);
		$this->assertEquals(
				$expectedPrettyPrint,
				$prettyPrinter->prettyPrint($template->getStatements($placeholders))
		);
	}
	
    /**
     * @dataProvider provideTestPlaceholderReplacement
     * @covers \PHPParser\Template
     */
    public function testPlaceholderReplacement($templateCode, $placeholders, $expectedPrettyPrint) {
        $parser = new Parser(new Lexer);
        $prettyPrinter = new PrettyPrinterDefault;

        $template = new Template($parser, $templateCode);
        $this->assertEquals(
            $expectedPrettyPrint,
            $prettyPrinter->prettyPrint($template->getStatements($placeholders))
        );
    }

    public function provideTestPlaceholderReplacement() {
        return array(
            array(
                '<?php $__name__ + $__Name__;',
                array('name' => 'foo'),
                '$foo + $Foo;'
            ),
            array(
                '<?php $__name__ + $__Name__;',
                array('Name' => 'Foo'),
                '$foo + $Foo;'
            ),
            array(
                '<?php $__name__ + $__Name__;',
                array('name' => 'foo', 'Name' => 'Bar'),
                '$foo + $Bar;'
            ),
            array(
                '<?php $__name__ + $__Name__;',
                array('Name' => 'Bar', 'name' => 'foo'),
                '$foo + $Bar;'
            ),
            array(
                '<?php $prefix__Name__Suffix;',
                array('name' => 'infix'),
                '$prefixInfixSuffix;'
            ),
            array(
                '<?php $___name___;',
                array('name' => 'foo'),
                '$_foo_;'
            ),
            array(
                '<?php $foobar;',
                array(),
                '$foobar;'
            ),
        );
    }
}
