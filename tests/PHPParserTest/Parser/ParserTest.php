<?php

namespace PHPParserTest;

use \PHPParser\Node\Dumper;

use \PHPParser\Lexer\Emulative;

use \PHPParser\Parser\Parser;

require_once dirname(__FILE__) . '/../PrettyPrinter/CodeTestAbstract.php';

class ParserTest extends CodeTestAbstract
{
	
    /**
     * @dataProvider provideTestParse
     */
    public function testParse($name, $code, $dump) {
        $parser = new Parser(new Emulative);
        $dumper = new Dumper;

        $Statements = $parser->parse($code);
        $this->assertEquals(
            $this->canonicalize($dump),
            $this->canonicalize($dumper->dump($Statements)),
            $name
        );
    }

    public function provideTestParse() {
        return $this->getTests(dirname(__FILE__) . '/../../code/parser', 'test');
    }

    /**
     * @dataProvider provideTestParseFail
     */
    public function testParseFail($name, $code, $msg) {
        $parser = new Parser(new Emulative);

        try {
            $parser->parse($code);

            $this->fail(sprintf('"%s": Expected \PHPParser\Error\Error', $name));
        } catch (\PHPParser\Error\Error $e) {
            $this->assertEquals($msg, $e->getMessage(), $name);
        }
    }

    public function provideTestParseFail() {
        return $this->getTests(dirname(__FILE__) . '/../../code/parser', 'test-fail');
    }
}
