<?php

namespace PHPParserTest\Builder;

use PHPParser\Node\ParamNode;

use PHPParser\Node\MagicConstant\DirMagicConstant;

use PHPParser\Node\NameNode;

use \PHPParser\Builder\ParamBuilder;
use \PHPParser\Node\Expression\ArrayExpression;
use \PHPParser\Node\Expression\ArrayItemExpression;
use \PHPParser\Node\Expression\ConstFetchExpression;
use \PHPParser\Node\Name;
use \PHPParser\Node\Param;
use \PHPParser\Node\Scalar\DirConstScalar;
use \PHPParser\Node\Scalar\DNumberScalar;
use \PHPParser\Node\Scalar\LNumberScalar;
use \PHPParser\Node\Scalar\StringScalar;

class ParamBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function createParamBuilder($name) {
		return new ParamBuilder($name);
	}

	/**
	 * @dataProvider provideTestDefaultValues
	 */
	public function testDefaultValues($value, $expectedValueNode) {
		$node = $this->createParamBuilder('test')
		->setDefault($value)
		->getNode()
		;

		$this->assertEquals($expectedValueNode, $node->default);
	}

	public function provideTestDefaultValues() {
		return array(
				array(
						null,
						new ConstFetchExpression(new NameNode('null'))
				),
				array(
						true,
						new ConstFetchExpression(new NameNode('true'))
				),
				array(
						false,
						new ConstFetchExpression(new NameNode('false'))
				),
				array(
						31415,
						new LNumberScalar(31415)
				),
				array(
						3.1415,
						new DNumberScalar(3.1415)
				),
				array(
						'Hallo World',
						new StringScalar('Hallo World')
				),
				array(
						array(1, 2, 3),
						new ArrayExpression(array(
								new ArrayItemExpression(new LNumberScalar(1)),
								new ArrayItemExpression(new LNumberScalar(2)),
								new ArrayItemExpression(new LNumberScalar(3)),
						))
				),
				array(
						array('foo' => 'bar', 'bar' => 'foo'),
						new ArrayExpression(array(
								new ArrayItemExpression(
										new StringScalar('bar'),
										new StringScalar('foo')
								),
								new ArrayItemExpression(
										new StringScalar('foo'),
										new StringScalar('bar')
								),
						))
				),
				array(
						new DirMagicConstant(),
						new DirMagicConstant()
				)
		);
	}

	public function testTypeHints() {
		$node = $this->createParamBuilder('test')
		->setTypeHint('array')
		->getNode()
		;

		$this->assertEquals(
				new ParamNode('test', null, 'array'),
				$node
		);

		$node = $this->createParamBuilder('test')
		->setTypeHint('callable')
		->getNode()
		;

		$this->assertEquals(
				new ParamNode('test', null, 'callable'),
				$node
		);

		$node = $this->createParamBuilder('test')
		->setTypeHint('Some\Class')
		->getNode()
		;

		$this->assertEquals(
				new ParamNode('test', null, new NameNode('Some\Class')),
				$node
		);
	}

	public function testByRef() {
		$node = $this->createParamBuilder('test')
		->makeByRef()
		->getNode()
		;

		$this->assertEquals(
				new ParamNode('test', null, null, true),
				$node
		);
	}
}
