<?php

namespace PHPParserTest\Builder;

use PHPParser\Node\Statement\PropertyPropertyStatement;

use PHPParser\Node\MagicConstant\DirMagicConstant;

use PHPParser\Node\NameNode;

use \PHPParser\Builder\PropertyBuilder;
use \PHPParser\Node\Expression\ArrayItemExpression;
use \PHPParser\Node\Expression\ArrayExpression;
use \PHPParser\Node\Expression\ConstFetchExpression;
use \PHPParser\Node\Name;
use \PHPParser\Node\Scalar\DirConstScalar;
use \PHPParser\Node\Scalar\StringScalar;
use \PHPParser\Node\Scalar\DNumberScalar;
use \PHPParser\Node\Scalar\LNumberScalar;
use \PHPParser\Node\Statement\PropertyStatement;
use \PHPParser\Node\Statement\ClassStatement;

class PropertyBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function createPropertyBuilder($name) {
		return new PropertyBuilder($name);
	}

	public function testModifiers() {
		$node = $this->createPropertyBuilder('test')
		->makePrivate(-1)
		->makeStatic(-1)
		->getNode()
		;

		$this->assertEquals(
				new PropertyStatement(
						ClassStatement::MODIFIER_PRIVATE
						| ClassStatement::MODIFIER_STATIC,
						array(
								new PropertyPropertyStatement('test')
						)
				),
				$node
		);

		$node = $this->createPropertyBuilder('test')
		->makeProtected(-1)
		->getNode()
		;

		$this->assertEquals(
				new PropertyStatement(
						ClassStatement::MODIFIER_PROTECTED,
						array(
								new PropertyPropertyStatement('test')
						)
				),
				$node
		);

		$node = $this->createPropertyBuilder('test')
		->makePublic(-1)
		->getNode()
		;

		$this->assertEquals(
				new PropertyStatement(
						ClassStatement::MODIFIER_PUBLIC,
						array(
								new PropertyPropertyStatement('test')
						)
				),
				$node
		);
	}

	/**
	 * @dataProvider provideTestDefaultValues
	 */
	public function testDefaultValues($value, $expectedValueNode) {
		$node = $this->createPropertyBuilder('test')
		->setDefault($value)
		->getNode()
		;

		$this->assertEquals($expectedValueNode, $node->props[0]->default);
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
}
