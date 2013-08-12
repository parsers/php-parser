<?php

namespace PHPParserTest\Parser;

use PHPParser\Parser\DocCommentParser;

class DocCommentParserTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * 
	 * 
	 */
	public function testParseLine() {
		
		$docComment = '/**
	 * Description title
	 * 
	 * Additional descprition
	 * 
	 * @author Hassan
	 * @package Test
	 * 
	 */';
		
		$parser = new DocCommentParser($docComment);
		
		$this->assertEquals('', $parser->getDesc());
		
		$parser->parse();
		
		$this->assertEquals('Description title', $parser->getShortDesc());
		
		$this->assertEquals('Additional descprition', $parser->getDesc());
		
		$this->assertEquals(array(
				'author' => 'Hassan',
				'package' => 'Test',
				), $parser->getParams());
		
	}
}