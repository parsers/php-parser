<?php

namespace PHPParserTest\Node;

use \PHPParser\Node\Node_Const;

use \PHPParser\Comment\DocComment;
use \PHPParser\Comment\Comment;

class NodeAbstractTest extends \PHPUnit_Framework_TestCase
{
	
	public function constructNode() {
		$attributes = array(
				'startLine' => 10,
				'comments'  => array(
						new Comment('// Comment' . "\n"),
						new DocComment('/** doc comment */'),
				),
		);
		
		$node = $this->getMockForAbstractClass(
				'\PHPParser\Node\NodeAbstract',
				array(
						array(
								'subNode' => 'value'
						),
						$attributes
				)
		);
		return $node;
	}
	
	public function testConstruct() {
		
		$attributes = array(
				'startLine' => 10,
				'comments'  => array(
						new Comment('// Comment' . "\n"),
						new DocComment('/** doc comment */'),
				),
		);

		$node = $this->getMockForAbstractClass(
				'\PHPParser\Node\NodeAbstract',
				array(
						array(
								'subNode' => 'value'
						),
						$attributes
				)
		);

		$this->assertEquals(array('subNode'), $node->getSubNodeNames());
		$this->assertEquals(10, $node->getLine());
		
		$comments = $node->getAttribute('comments');
		
		$this->assertEquals(
				array(
						new Comment('// Comment' . "\n"),
						new DocComment('/** doc comment */'),
				),
				$comments);
		
		$this->assertEquals(2, count($comments));
		
		$docComment = new DocComment('/** doc comment */');
		$lastComment = $comments[count($comments) - 1];
		$this->assertEquals($docComment, $lastComment);
		
		$this->assertEquals('PHPParser\Comment\DocComment', get_class($lastComment));
		
		$this->assertEquals(true, $lastComment instanceof DocComment);
		
		$this->assertEquals('/** doc comment */', $node->getDocComment());
		$this->assertEquals('value', $node->subNode);
		$this->assertTrue(isset($node->subNode));
		$this->assertEquals($attributes, $node->getAttributes());

		return $node;
	}

	/**
	 * @depends testConstruct
	 */
	public function testGetDocComment() {
		
		$node = $this->constructNode();
		
		$this->assertEquals('/** doc comment */', $node->getDocComment());
		array_pop($node->getAttribute('comments')); // remove doc comment
		$this->assertNull($node->getDocComment());
		array_pop($node->getAttribute('comments')); // remove comment
		$this->assertNull($node->getDocComment());
	}

	/**
	 * @depends testConstruct
	 */
	public function testChange() {
		
		$node = $this->getMockForAbstractClass(
				'\PHPParser\Node\NodeAbstract');
		
		// change of line
		$node->setLine(15);
		$this->assertEquals(15, $node->getLine());

		// direct modification
		$node->subNode = 'newValue';
		$this->assertEquals('newValue', $node->subNode);

		// indirect modification
		$subNode =& $node->subNode;
		$subNode = 'newNewValue';
		$this->assertEquals('newNewValue', $node->subNode);

		// removal
		unset($node->subNode);
		$this->assertFalse(isset($node->subNode));
	}

	public function testAttributes() {
		/** @var $node \PHPParser\Node */
		$node = $this->getMockForAbstractClass('\PHPParser\Node\NodeAbstract');

		$this->assertEmpty($node->getAttributes());

		$node->setAttribute('key', 'value');
		$this->assertTrue($node->hasAttribute('key'));
		$this->assertEquals('value', $node->getAttribute('key'));

		$this->assertFalse($node->hasAttribute('doesNotExist'));
		$this->assertNull($node->getAttribute('doesNotExist'));
		$this->assertEquals('default', $node->getAttribute('doesNotExist', 'default'));

		$node->setAttribute('null', null);
		$this->assertTrue($node->hasAttribute('null'));
		$this->assertNull($node->getAttribute('null'));
		$this->assertNull($node->getAttribute('null', 'default'));

		$this->assertEquals(
				array(
						'key'  => 'value',
						'null' => null,
				),
				$node->getAttributes()
		);
	}
}
