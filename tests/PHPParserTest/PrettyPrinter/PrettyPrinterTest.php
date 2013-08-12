<?php

namespace PHPParserTest;

use \PHPParser\Lexer\Emulative;

use \PHPParser\PrettyPrinter\PrettyPrinterDefault;

use \PHPParser\Parser\Parser;

require_once dirname(__FILE__) . '/CodeTestAbstract.php';

class PrettyPrinterTest extends CodeTestAbstract
{
    protected function doTestPrettyPrintMethod($method, $name, $code, $dump) {
        $parser = new Parser(new Emulative);
        $prettyPrinter = new PrettyPrinterDefault;

        $Statements = $parser->parse($code);
        $this->assertEquals(
            $this->canonicalize($dump),
            $this->canonicalize($prettyPrinter->$method($Statements)),
            $name
        );
    }

    /**
     * @dataProvider provideTestPrettyPrint
     * @covers \PHPParser\PrettyPrinter_Default<extended>
     */
    public function testPrettyPrint($name, $code, $dump) {
        $this->doTestPrettyPrintMethod('prettyPrint', $name, $code, $dump);
    }

    /**
     * @dataProvider provideTestPrettyPrintFile
     * @covers \PHPParser\PrettyPrinter_Default<extended>
     */
    public function testPrettyPrintFile($name, $code, $dump) {
        $this->doTestPrettyPrintMethod('prettyPrintFile', $name, $code, $dump);
    }

    public function provideTestPrettyPrint() {
        return $this->getTests(dirname(__FILE__) . '/../../code/prettyPrinter', 'test');
    }

    public function provideTestPrettyPrintFile() {
        return $this->getTests(dirname(__FILE__) . '/../../code/prettyPrinter', 'file-test');
    }
}
