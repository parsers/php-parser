<?php

namespace PHPParser\Builder;

use PHPParser\Node\Scalar\StringScalar;

use PHPParser\Node\Scalar\DNumberScalar;

use PHPParser\Node\Scalar\LNumberScalar;

use PHPParser\Node\NameNode;

use \PHPParser\Node\Expression\ArrayExpression;
use \PHPParser\Node\Expression\ArrayItemExpression;
use \PHPParser\Node\Scalar\String;
use \PHPParser\Node\Scalar\DNumber;
use \PHPParser\Node\Scalar\LNumber;
use \PHPParser\Node\Expression\ConstFetchExpression;
use \PHPParser\Node\Statement\ClassStatement;
use \PHPParser\Node\Name;

abstract class BuilderAbstract implements BuilderInterface {
	/**
	 * Normalizes a node: Converts builder objects to nodes.
	 *
	 * @param \PHPParser\Node|\PHPParser\Builder $node The node to normalize
	 *
	 * @return \PHPParser\Node The normalized node
	 */
	protected function normalizeNode($node) {
		
		if ($node instanceof \PHPParser\Builder\BuilderAbstract) {
			return $node->getNode();
		} elseif ($node instanceof \PHPParser\Node\NodeAbstract) {
			return $node;
		}

		throw new \LogicException('Expected node or builder object');
	}

	/**
	 * Normalizes a name: Converts plain string names to Name.
	 *
	 * @param Name|string $name The name to normalize
	 *
	 * @return Name The normalized name
	 */
	protected function normalizeName($name) {
		if ($name instanceof NameNode) {
			return $name;
		} else {
			return new NameNode($name);
		}
	}

	/**
	 * Normalizes a value: Converts nulls, booleans, integers,
	 * floats, strings and arrays into their respective nodes
	 *
	 * @param mixed $value The value to normalize
	 *
	 * @return Expr The normalized value
	 */
	protected function normalizeValue($value) {
		if ($value instanceof \PHPParser\Node\NodeInterface) {
			return $value;
		} elseif (is_null($value)) {
			return new ConstFetchExpression(
					new NameNode('null')
			);
		} elseif (is_bool($value)) {
			return new ConstFetchExpression(
					new NameNode($value ? 'true' : 'false')
			);
		} elseif (is_int($value)) {
			return new LNumberScalar($value);
		} elseif (is_float($value)) {
			return new DNumberScalar($value);
		} elseif (is_string($value)) {
			return new StringScalar($value);
		} elseif (is_array($value)) {
			$items = array();
			$lastKey = -1;
			foreach ($value as $itemKey => $itemValue) {
				// for consecutive, numeric keys don't generate keys
				if (null !== $lastKey && ++$lastKey === $itemKey) {
					$items[] = new ArrayItemExpression(
							$this->normalizeValue($itemValue)
					);
				} else {
					$lastKey = null;
					$items[] = new ArrayItemExpression(
							$this->normalizeValue($itemValue),
							$this->normalizeValue($itemKey)
					);
				}
			}

			return new ArrayExpression($items);
		} else {
			throw new \LogicException('Invalid value');
		}
	}

	/**
	 * Sets a modifier in the $this->type property.
	 *
	 * @param int $modifier Modifier to set
	 */
	protected function setModifier($modifier, $line) {
		ClassStatement::verifyModifier($this->type, $modifier, $line);
		$this->type |= $modifier;
	}
}
