<?php

namespace PHPParserTest\Template;

use \PHPParser\Lexer\Lexer;

use \PHPParser\Parser\Parser;

use \PHPParser\Template\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWithoutSuffix() {
        $templateLoader = new Loader(
            new Parser(new Lexer),
            dirname(__FILE__)
        );

        // load this file as a template, as we don't really care about the contents
        $template = $templateLoader->load('LoaderTest.php');
        $this->assertInstanceOf('\PHPParser\Template\Template', $template);
    }

    public function testLoadWithSuffix() {
        $templateLoader = new Loader(
            new Parser(new Lexer),
            dirname(__FILE__), '.php'
        );

        // load this file as a template, as we don't really care about the contents
        $template = $templateLoader->load('LoaderTest');
        $this->assertInstanceOf('\PHPParser\Template\Template', $template);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonexistentBaseDirectoryError() {
        new Loader(
            new Parser(new Lexer),
            dirname(__FILE__) . '/someDirectoryThatDoesNotExist'
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonexistentFileError() {
        $templateLoader = new Loader(
            new Parser(new Lexer),
            dirname(__FILE__)
        );

        $templateLoader->load('SomeTemplateThatDoesNotExist');
    }
}
