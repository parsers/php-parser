<?php

namespace PHPParserTest\Unserializer;

use PHPParser\Node\MagicConstant\ClassMagicConstant;

use PHPParser\Node\Scalar\StringScalar;

use \PHPParser\Comment\DocComment;
use \PHPParser\Node\Scalar\ClassConstStatement;
use \PHPParser\Comment\Comment;
use \PHPParser\Node\Scalar\String;
use \PHPParser\Unserializer\XMLUnserializer;

class XMLUnserializerTest extends \PHPUnit_Framework_TestCase
{
	public function testNode() {
		$xml = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'node.xml');

		$unserializer  = new XMLUnserializer();
		$this->assertEquals(
				new StringScalar('Test', array(
						'startLine' => 1,
						'comments'  => array(
								new Comment('// comment' . "\n", 2),
								new DocComment('/** doc comment */', 3),
						),
				)),
				$unserializer->unserialize($xml)
		);
	}

	public function testEmptyNode() {
		$xml = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'emptynode.xml');

		$unserializer  = new XMLUnserializer();

		$this->assertEquals(
				new ClassMagicConstant(),
				$unserializer->unserialize($xml)
		);
	}

	public function testScalars() {
		$xml = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'scalars.xml');
		$result = array(
				array(), array(),
				'test', '', '',
				1,
				1, 1.5,
				true, false, null
		);

		$unserializer  = new XMLUnserializer();
		$this->assertEquals($result, $unserializer->unserialize($xml));
	}

	/**
	 * @expectedException        DomainException
	 * @expectedExceptionMessage AST root element not found
	 */
	public function testWrongRootElementError() {
		$xml = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'WrongRootElementError.xml');

		$unserializer = new XMLUnserializer();
		$unserializer->unserialize($xml);
	}

	/**
	 * @dataProvider             provideTestErrors
	 */
	public function testErrors($xml, $errorMsg) {
		$this->setExpectedException('\DomainException', $errorMsg);

		$xml =
		<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<AST xmlns:scalar="http://nikic.github.com/PHPParser/XML/scalar"
     xmlns:node="http://nikic.github.com/PHPParser/XML/node"
     xmlns:subNode="http://nikic.github.com/PHPParser/XML/subNode"
     xmlns:foo="http://nikic.github.com/PHPParser/XML/foo">
$xml
</AST>
XML;

		$unserializer = new XMLUnserializer();
		$unserializer->unserialize($xml);
	}

	public function provideTestErrors() {
		return array(
				array('<scalar:true>test</scalar:true>',   '"true" scalar must be empty'),
				array('<scalar:false>test</scalar:false>', '"false" scalar must be empty'),
				array('<scalar:null>test</scalar:null>',   '"null" scalar must be empty'),
				array('<scalar:foo>bar</scalar:foo>',      'Unknown scalar type "foo"'),
				array('<scalar:int>x</scalar:int>',        '"x" is not a valid int'),
				array('<scalar:float>x</scalar:float>',    '"x" is not a valid float'),
				array('',                                  'Expected node or scalar'),
				array('<foo:bar>test</foo:bar>',           'Unexpected node of type "foo:bar"'),
				array(
						'<node:Scalar_String><foo:bar>test</foo:bar></node:Scalar_String>',
						'Expected sub node or attribute, got node of type "foo:bar"'
				),
				array(
						'<node:Scalar_String><subNode:value/></node:Scalar_String>',
						'Expected node or scalar'
				),
		);
	}
}
