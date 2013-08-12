<?php

namespace PHPParserTest\Builder;

use PHPParser\Node\Statement\PropertyPropertyStatement;

use PHPParser\Node\NameNode;
use PHPParser\Node\ConstNode;
use \PHPParser\Builder\InterfaceBuilder;
use \PHPParser\Node\Scalar\DNumberScalar;
use \PHPParser\Node\Statement\PropertyStatement;
use \PHPParser\Node\Statement\ClassConstStatement;
use \PHPParser\Node\Statement\ClassMethodStatement;
use \PHPParser\Node\Name;
use \PHPParser\Node\Statement\InterfaceStatement;
use \PHPParser\PrettyPrinter\PrettyPrinterDefault;

/**
 * This class unit-tests the interface builder
 */
class InterfaceBuilderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * 
	 * @var \PHPParser\Builder\InterfaceBuilder
	 */
	protected $builder = null;

	protected function setUp() {
		$this->builder = new InterfaceBuilder('Contract');
	}

	private function dump($node) {
		$pp = new PrettyPrinterDefault();
		return $pp->prettyPrint(array($node));
	}

	public function testEmpty() {
		$contract = $this->builder->getNode();
		$this->assertInstanceOf('\PHPParser\Node\Statement\InterfaceStatement', $contract);
		$this->assertEquals('Contract', $contract->name);
	}

	public function testExtending() {
		$contract = $this->builder->extend('Space\Root1', 'Root2')->getNode();
		$this->assertEquals(
				new InterfaceStatement('Contract', array(
						'extends' => array(
								new NameNode('Space\Root1'),
								new NameNode('Root2')
						),
				)), $contract
		);
	}

	public function testAddMethod() {
		$method = new ClassMethodStatement('doSomething');
		$contract = $this->builder->addStatement($method)->getNode();
		$this->assertEquals(array($method), $contract->Statements);
	}

	public function testAddConst() {
		$const = new ClassConstStatement(array(
				new ConstNode('SPEED_OF_LIGHT', new \PHPParser\Node\Scalar\DNumberScalar(299792458))
		));
		$contract = $this->builder->addStatement($const)->getNode();
		$this->assertEquals(299792458, $contract->Statements[0]->consts[0]->value->value);
	}

	public function testOrder() {
		$const = new ClassConstStatement(array(
				new ConstNode('SPEED_OF_LIGHT', new \PHPParser\Node\Scalar\DNumberScalar(299792458))
		));
		$method = new ClassMethodStatement('doSomething');
		$contract = $this->builder
		->addStatement($method)
		->addStatement($const)
		->getNode()
		;

		$this->assertInstanceOf('\PHPParser\Node\Statement\ClassConstStatement', $contract->Statements[0]);
		$this->assertInstanceOf('\PHPParser\Node\Statement\ClassMethodStatement', $contract->Statements[1]);
	}

	/**
	 * @expectedException LogicException
	 * @expectedExceptionMessage Unexpected node of type "PHPParser\Node\Statement\PropertyPropertyStatement"
	 */
	public function testInvalidStatementError() {
		$this->builder->addStatement(new PropertyPropertyStatement('invalid'));
	}

	public function testFullFunctional() {
		$const = new ClassConstStatement(array(
				new ConstNode('SPEED_OF_LIGHT', new \PHPParser\Node\Scalar\DNumberScalar(299792458))
		));
		$method = new ClassMethodStatement('doSomething');
		$contract = $this->builder
		->addStatement($method)
		->addStatement($const)
		->getNode()
		;

		eval($this->dump($contract));

		$this->assertTrue(interface_exists('Contract', false));
	}
}

