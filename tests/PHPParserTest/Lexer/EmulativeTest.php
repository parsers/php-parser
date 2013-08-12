<?php

namespace PHPParserTest\Lexer;

use PHPParser\Token\ArrayToken;

use \PHPParser\Lexer\Emulative;

use \PHPParser\Parser\Parser;

class EmulativeTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPParser\Lexer_Emulative */
    protected $lexer;

    protected function setUp() {
        $this->lexer = new Emulative;
    }

    /**
     * @dataProvider provideTestReplaceKeywords
     */
    public function testReplaceKeywords($keyword, $expectedToken) {
    	
        $this->lexer->startLexing('<?php ' . $keyword);
        
        $arrayToken = new ArrayToken();
        //$this->assertEquals($arrayToken, $this->lexer->getNextToken());
        
        $this->assertEquals($expectedToken, $this->lexer->getNextToken());
        $this->assertEquals(0, $this->lexer->getNextToken());
    }

    /**
     * @dataProvider provideTestReplaceKeywords
     */
    public function testNoReplaceKeywordsAfterObjectOperator($keyword) {
        $this->lexer->startLexing('<?php ->' . $keyword);

        $this->assertEquals(Parser::T_OBJECT_OPERATOR, $this->lexer->getNextToken());
        $this->assertEquals(Parser::T_STRING, $this->lexer->getNextToken());
        $this->assertEquals(0, $this->lexer->getNextToken());
    }

    public function provideTestReplaceKeywords() {
        return array(
            // PHP 5.5
            array('finally',       Parser::T_FINALLY),
            array('yield',         Parser::T_YIELD),

            // PHP 5.4
            array('callable',      Parser::T_CALLABLE),
            array('insteadof',     Parser::T_INSTEADOF),
            array('trait',         Parser::T_TRAIT),
            array('__TRAIT__',     Parser::T_TRAIT_C),

            // PHP 5.3
            array('__DIR__',       Parser::T_DIR),
            array('goto',          Parser::T_GOTO),
            array('namespace',     Parser::T_NAMESPACE),
            array('__NAMESPACE__', Parser::T_NS_C),
        );
    }

    /**
     * @dataProvider provideTestLexNewFeatures
     */
    public function testLexNewFeatures($code, array $expectedTokens) {
    	$this->lexer->startLexing('<?php ' . $code);

    	foreach ($expectedTokens as $expectedToken) {
    		list($expectedTokenType, $expectedTokenText) = $expectedToken;
    		$token = $this->lexer->getNextToken($text);
    		$this->assertEquals($expectedTokenType, $token);
    		$this->assertEquals($expectedTokenText, $text);
    	}
    	$this->assertEquals(0, $this->lexer->getNextToken());
    }
    
    public function testLeaveStuffAloneInStrings1() {
    	
    	$code = '0b1010110';
    	//$text = array(
          //      array(Parser::T_LNUMBER, '0b1010110'),
            //);
    	
    	$stringifiedToken = '"' . addcslashes($code, '"\\') . '"';
    
    	$this->assertEquals(
    			array(
    					array(
    							Parser::T_DOC_COMMENT,
    							'<?php ',
    							1
    					),
    					array(
    							Parser::T_CONSTANT_ENCAPSED_STRING,
    							'"0b1010110"',
    							1
    					)
    			),
    			@token_get_all('<?php ' . $stringifiedToken)
    	);
    
    	$this->lexer->startLexing('<?php ' . $stringifiedToken);
    
    	$tokenObj = $this->lexer->getNextToken($text);
    	
    	$this->assertEquals(Parser::T_CONSTANT_ENCAPSED_STRING, $tokenObj);
    	
    	
    	
    	$this->assertEquals($stringifiedToken, $text);
    	$this->assertEquals(0, $this->lexer->getNextToken());
    }

    /**
     * @dataProvider provideTestLexNewFeatures
     */
    public function testLeaveStuffAloneInStrings($code, $text) {
        $stringifiedToken = '"' . addcslashes($code, '"\\') . '"';
        
        $this->lexer->startLexing('<?php ' . $stringifiedToken);

        $tokenObj = $this->lexer->getNextToken($text);
        
        $this->assertEquals(Parser::T_CONSTANT_ENCAPSED_STRING, $tokenObj);
        
        
        
        $this->assertEquals($stringifiedToken, $text);
        $this->assertEquals(0, $this->lexer->getNextToken());
    }

    public function provideTestLexNewFeatures() {
        return array(
            array('0b1010110', array(
                array(Parser::T_LNUMBER, '0b1010110'),
            )),
            array('0b1011010101001010110101010010101011010101010101101011001110111100', array(
                array(Parser::T_DNUMBER, '0b1011010101001010110101010010101011010101010101101011001110111100'),
            )),
            array('\\', array(
                array(Parser::T_NS_SEPARATOR, '\\'),
            )),
            array("<<<'NOWDOC'\nNOWDOC;\n", array(
                array(Parser::T_START_HEREDOC, "<<<'NOWDOC'\n"),
                array(Parser::T_END_HEREDOC, 'NOWDOC'),
                array(ord(';'), ';'),
            )),
            array("<<<'NOWDOC'\nFoobar\nNOWDOC;\n", array(
                array(Parser::T_START_HEREDOC, "<<<'NOWDOC'\n"),
                array(Parser::T_ENCAPSED_AND_WHITESPACE, "Foobar\n"),
                array(Parser::T_END_HEREDOC, 'NOWDOC'),
                array(ord(';'), ';'),
            )),
        );
    }
}
