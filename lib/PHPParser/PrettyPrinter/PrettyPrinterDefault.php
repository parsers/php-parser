<?php

namespace PHPParser\PrettyPrinter;

use PHPParser\Node\Expression\ExitExpression;

use PHPParser\Node\Expression\ClosureUseExpression;

use PHPParser\Node\Expression\IncludeExpression;

use PHPParser\Node\Statement\CatchStatement;

use PHPParser\Node\Statement\TryCatchStatement;

use PHPParser\Node\Expression\ConstFetchExpression;

use PHPParser\Node\Expression\NewExpression;

use PHPParser\Node\Expression\ClassConstFetchExpression;

use PHPParser\Node\Expression\StaticPropertyFetchExpression;

use PHPParser\Node\Expression\IssetExpression;

use PHPParser\Node\Expression\EmptyExpression;

use PHPParser\Node\Expression\ArrayDimFetchExpression;

use PHPParser\Node\Expression\StaticCallExpression;

use PHPParser\Node\Expression\MethodCallExpression;

use PHPParser\Node\Expression\FuncCallExpression;

use PHPParser\Node\Expression\Cast_Unset;

use PHPParser\Node\Expression\Cast_Bool;

use PHPParser\Node\Expression\Cast_Object;

use PHPParser\Node\Expression\Cast_Array;

use PHPParser\Node\Expression\Cast_String;

use PHPParser\Node\Expression\Cast_Double;

use PHPParser\Node\Expression\Cast_Int;

use PHPParser\Node\Expression\ErrorSuppressExpression;

use PHPParser\Node\Expression\PostDecExpression;

use PHPParser\Node\Expression\PostIncExpression;

use PHPParser\Node\Expression\PreDecExpression;

use PHPParser\Node\Expression\PreIncExpression;

use PHPParser\Node\Expression\UnaryPlusExpression;

use PHPParser\Node\Expression\UnaryMinusExpression;

use PHPParser\Node\Expression\BitwiseNotExpression;

use PHPParser\Node\Expression\BooleanNotExpression;

use PHPParser\Node\Expression\InstanceofExpression;

use PHPParser\Node\Expression\SmallerOrEqualExpression;

use PHPParser\Node\Expression\SmallerExpression;

use PHPParser\Node\Expression\GreaterOrEqualExpression;

use PHPParser\Node\Expression\GreaterExpression;

use PHPParser\Node\Expression\NotIdenticalExpression;

use PHPParser\Node\Expression\IdenticalExpression;

use PHPParser\Node\Expression\NotEqualExpression;

use PHPParser\Node\Expression\EqualExpression;

use PHPParser\Node\Expression\LogicalXorExpression;

use PHPParser\Node\Expression\LogicalOrExpression;

use PHPParser\Node\Expression\LogicalAndExpression;

use PHPParser\Node\Expression\ShiftRightExpression;

use PHPParser\Node\Expression\ShiftLeftExpression;

use PHPParser\Node\Expression\BitwiseXorExpression;

use PHPParser\Node\Expression\BitwiseOrExpression;

use PHPParser\Node\Expression\BitwiseAndExpression;

use PHPParser\Node\Expression\BooleanOrExpression;

use PHPParser\Node\Expression\BooleanAndExpression;

use PHPParser\Node\Expression\ModExpression;

use PHPParser\Node\Expression\ConcatExpression;

use PHPParser\Node\Expression\DivExpression;

use PHPParser\Node\Expression\MulExpression;

use PHPParser\Node\Expression\MinusExpression;

use PHPParser\Node\Expression\PlusExpression;

use PHPParser\Node\Expression\VariableExpression;

use PHPParser\Node\Expression\AssignMinusExpression;

use PHPParser\Node\Expression\AssignPlusExpression;

use Expression\AssignRefExpression;

use PHPParser\Node\Expression\AssignExpression;

use PHPParser\Node\Scalar\DNumberScalar;

use PHPParser\Node\Scalar\LNumberScalar;

use PHPParser\Node\Scalar\EncapsedScalar;

use PHPParser\Node\Scalar\StringScalar;

use PHPParser\Node\MagicConstant\MagicConstantAbstract;

use PHPParser\Node\Scalar\NSConstScalar;

use PHPParser\Node\Statement\ClassConstStatement;

use PHPParser\Node\Scalar\MethodConstScalar;

use PHPParser\Node\Scalar\LineConstScalar;

use PHPParser\Node\Scalar\FuncConstScalar;

use PHPParser\Node\Scalar\FileConstScalar;

use PHPParser\Node\Scalar\DirConstScalar;

class PrettyPrinterDefault extends PrettyPrinterAbstract
{
	// Special nodes

	public function printParamNode(\PHPParser\Node\ParamNode $node) {
		return ($node->type ? (is_string($node->type) ? $node->type : $this->p($node->type)) . ' ' : '')
		. ($node->byRef ? '&' : '')
		. '$' . $node->name
		. ($node->default ? ' = ' . $this->p($node->default) : '');
	}

	public function printArgNode(\PHPParser\Node\ArgNode $node) {
		return ($node->byRef ? '&' : '') . $this->p($node->value);
	}

	public function printConstNode(\PHPParser\Node\ConstNode $node) {
		return $node->name . ' = ' . $this->p($node->value);
	}

	// Names

	public function printNameNode(\PHPParser\Node\NameNode $node) {
		return implode('\\', $node->parts);
	}

	public function printFullyQualifiedNameNode(\PHPParser\Node\FullyQualifiedNameNode $node) {
		return '\\' . implode('\\', $node->parts);
	}

	public function printRelativeNameNode(\PHPParser\Node\RelativeNameNode $node) {
		return 'namespace\\' . implode('\\', $node->parts);
	}

	// Magic Constants

	public function printMagicConstant(MagicConstantAbstract $node) {
		return (string) $node;
	}

	// Scalars

	public function printStringScalar(StringScalar $node) {
		return '\'' . $this->pNoIndent(addcslashes($node->value, '\'\\')) . '\'';
	}

	public function printEncapsedScalar(EncapsedScalar $node) {
		return '"' . $this->pEncapsList($node->parts, '"') . '"';
	}

	public function printLNumberScalar(LNumberScalar $node) {
		return (string) $node->value;
	}

	public function printDNumberScalar(DNumberScalar $node) {
		$stringValue = (string) $node->value;

		// ensure that number is really printed as float
		return ctype_digit($stringValue) ? $stringValue . '.0' : $stringValue;
	}

	// Assignments

	public function printAssignExpression(AssignExpression $node) {
		return $this->pInfixOp('AssignExpression', $node->var, ' = ', $node->expr);
	}

	public function printAssignRefExpression(AssignRefExpression $node) {
		return $this->pInfixOp('AssignRefExpression', $node->var, ' =& ', $node->expr);
	}

	public function printAssignPlusExpression(AssignPlusExpression $node) {
		return $this->pInfixOp('AssignPlusExpression', $node->var, ' += ', $node->expr);
	}

	public function printAssignMinusExpression(AssignMinusExpression $node) {
		return $this->pInfixOp('AssignMinusExpression', $node->var, ' -= ', $node->expr);
	}

	public function printAssignMulExpression(AssignMul $node) {
		return $this->pInfixOp('AssignMulExpression', $node->var, ' *= ', $node->expr);
	}

	public function printAssignDivExpression(AssignDiv $node) {
		return $this->pInfixOp('AssignDivExpression', $node->var, ' /= ', $node->expr);
	}

	public function printAssignConcatExpression(AssignConcat $node) {
		return $this->pInfixOp('AssignConcatExpression', $node->var, ' .= ', $node->expr);
	}

	public function printAssignModExpression(AssignMod $node) {
		return $this->pInfixOp('AssignModExpression', $node->var, ' %= ', $node->expr);
	}

	public function printAssignBitwiseAndExpression(AssignBitwiseAnd $node) {
		return $this->pInfixOp('AssignBitwiseAndExpression', $node->var, ' &= ', $node->expr);
	}

	public function printAssignBitwiseOrExpression(AssignBitwiseOr $node) {
		return $this->pInfixOp('AssignBitwiseOrExpression', $node->var, ' |= ', $node->expr);
	}

	public function printAssignBitwiseXorExpression(AssignBitwiseXor $node) {
		return $this->pInfixOp('AssignBitwiseXorExpression', $node->var, ' ^= ', $node->expr);
	}

	public function printAssignShiftLeftExpression(AssignShiftLeft $node) {
		return $this->pInfixOp('AssignShiftLeftExpression', $node->var, ' <<= ', $node->expr);
	}

	public function printAssignShiftRightExpression(AssignShiftRight $node) {
		return $this->pInfixOp('AssignShiftRightExpression', $node->var, ' >>= ', $node->expr);
	}

	// Binary expressions

	public function printPlusExpression(PlusExpression $node) {
		return $this->pInfixOp('PlusExpression', $node->left, ' + ', $node->right);
	}

	public function printMinusExpression(MinusExpression $node) {
		return $this->pInfixOp('MinusExpression', $node->left, ' - ', $node->right);
	}

	public function printMulExpression(MulExpression $node) {
		return $this->pInfixOp('MulExpression', $node->left, ' * ', $node->right);
	}

	public function printDivExpression(DivExpression $node) {
		return $this->pInfixOp('DivExpression', $node->left, ' / ', $node->right);
	}

	public function printConcatExpression(ConcatExpression $node) {
		return $this->pInfixOp('ConcatExpression', $node->left, ' . ', $node->right);
	}

	public function printModExpression(ModExpression $node) {
		return $this->pInfixOp('ModExpression', $node->left, ' % ', $node->right);
	}

	public function printBooleanAndExpression(BooleanAndExpression $node) {
		return $this->pInfixOp('BooleanAndExpression', $node->left, ' && ', $node->right);
	}

	public function printBooleanOrExpression(BooleanOrExpression $node) {
		return $this->pInfixOp('BooleanOrExpression', $node->left, ' || ', $node->right);
	}

	public function printBitwiseAndExpression(BitwiseAndExpression $node) {
		return $this->pInfixOp('BitwiseAndExpression', $node->left, ' & ', $node->right);
	}

	public function printBitwiseOrExpression(BitwiseOrExpression $node) {
		return $this->pInfixOp('BitwiseOrExpression', $node->left, ' | ', $node->right);
	}

	public function printBitwiseXorExpression(BitwiseXorExpression $node) {
		return $this->pInfixOp('BitwiseXorExpression', $node->left, ' ^ ', $node->right);
	}

	public function printShiftLeftExpression(ShiftLeftExpression $node) {
		return $this->pInfixOp('ShiftLeftExpression', $node->left, ' << ', $node->right);
	}

	public function printShiftRightExpression(ShiftRightExpression $node) {
		return $this->pInfixOp('ShiftRightExpression', $node->left, ' >> ', $node->right);
	}

	public function printLogicalAndExpression(LogicalAndExpression $node) {
		return $this->pInfixOp('LogicalAndExpression', $node->left, ' and ', $node->right);
	}

	public function printLogicalOrExpression(LogicalOrExpression $node) {
		return $this->pInfixOp('LogicalOrExpression', $node->left, ' or ', $node->right);
	}

	public function printLogicalXorExpression(LogicalXorExpression $node) {
		return $this->pInfixOp('LogicalXorExpression', $node->left, ' xor ', $node->right);
	}

	public function printEqualExpression(EqualExpression $node) {
		return $this->pInfixOp('EqualExpression', $node->left, ' == ', $node->right);
	}

	public function printNotEqualExpression(NotEqualExpression $node) {
		return $this->pInfixOp('NotEqualExpression', $node->left, ' != ', $node->right);
	}

	public function printIdenticalExpression(IdenticalExpression $node) {
		return $this->pInfixOp('IdenticalExpression', $node->left, ' === ', $node->right);
	}

	public function printNotIdenticalExpression(NotIdenticalExpression $node) {
		return $this->pInfixOp('NotIdenticalExpression', $node->left, ' !== ', $node->right);
	}

	public function printGreaterExpression(GreaterExpression $node) {
		return $this->pInfixOp('GreaterExpression', $node->left, ' > ', $node->right);
	}

	public function printGreaterOrEqualExpression(GreaterOrEqualExpression $node) {
		return $this->pInfixOp('GreaterOrEqualExpression', $node->left, ' >= ', $node->right);
	}

	public function printSmallerExpression(SmallerExpression $node) {
		return $this->pInfixOp('SmallerExpression', $node->left, ' < ', $node->right);
	}

	public function printSmallerOrEqualExpression(SmallerOrEqualExpression $node) {
		return $this->pInfixOp('SmallerOrEqualExpression', $node->left, ' <= ', $node->right);
	}

	public function printInstanceofExpression(InstanceofExpression $node) {
		return $this->pInfixOp('InstanceofExpression', $node->expr, ' instanceof ', $node->class);
	}

	// Unary expressions

	public function printBooleanNotExpression(BooleanNotExpression $node) {
		return $this->pPrefixOp('BooleanNotExpression', '!', $node->expr);
	}

	public function printBitwiseNotExpression(BitwiseNotExpression $node) {
		return $this->pPrefixOp('BitwiseNotExpression', '~', $node->expr);
	}

	public function printUnaryMinusExpression(UnaryMinusExpression $node) {
		return $this->pPrefixOp('UnaryMinusExpression', '-', $node->expr);
	}

	public function printUnaryPlusExpression(UnaryPlusExpression $node) {
		return $this->pPrefixOp('UnaryPlusExpression', '+', $node->expr);
	}

	public function printPreIncExpression(PreIncExpression $node) {
		return $this->pPrefixOp('PreIncExpression', '++', $node->var);
	}

	public function printPreDecExpression(PreDecExpression $node) {
		return $this->pPrefixOp('PreDecExpression', '--', $node->var);
	}

	public function printPostIncExpression(PostIncExpression $node) {
		return $this->pPostfixOp('PostIncExpression', $node->var, '++');
	}

	public function printPostDecExpression(PostDecExpression $node) {
		return $this->pPostfixOp('PostDecExpression', $node->var, '--');
	}

	public function printErrorSuppressExpression(ErrorSuppressExpression $node) {
		return $this->pPrefixOp('ErrorSuppressExpression', '@', $node->expr);
	}

	// Casts

	public function printCast_IntExpression(Cast_Int $node) {
		return $this->pPrefixOp('Cast_IntExpression', '(int) ', $node->expr);
	}

	public function printCast_DoubleExpression(Cast_Double $node) {
		return $this->pPrefixOp('Cast_DoubleExpression', '(double) ', $node->expr);
	}

	public function printCast_StringExpression(Cast_String $node) {
		return $this->pPrefixOp('Cast_StringExpression', '(string) ', $node->expr);
	}

	public function printCast_ArrayExpression(Cast_Array $node) {
		return $this->pPrefixOp('Cast_ArrayExpression', '(array) ', $node->expr);
	}

	public function printCast_ObjectExpression(Cast_Object $node) {
		return $this->pPrefixOp('Cast_ObjectExpression', '(object) ', $node->expr);
	}

	public function printCast_BoolExpression(Cast_Bool $node) {
		return $this->pPrefixOp('Cast_BoolExpression', '(bool) ', $node->expr);
	}

	public function printCast_UnsetExpression(Cast_Unset $node) {
		return $this->pPrefixOp('Cast_UnsetExpression', '(unset) ', $node->expr);
	}

	// Function calls and similar constructs

	public function printFuncCallExpression(FuncCallExpression $node) {
		return $this->p($node->name) . '(' . $this->pCommaSeparated($node->args) . ')';
	}

	public function printMethodCallExpression(MethodCallExpression $node) {
		return $this->pVarOrNewExpr($node->var) . '->' . $this->pObjectPropertyStatement($node->name)
		. '(' . $this->pCommaSeparated($node->args) . ')';
	}

	public function printStaticCallExpression(StaticCallExpression $node) {
		return $this->p($node->class) . '::'
		. ($node->name instanceof \PHPParser\Node\Expression\Expression
				? ($node->name instanceof VariableExpression
						|| $node->name instanceof ArrayDimFetchExpression
						? $this->p($node->name)
						: '{' . $this->p($node->name) . '}')
				: $node->name)
				. '(' . $this->pCommaSeparated($node->args) . ')';
	}

	public function printEmptyExpression(EmptyExpression $node) {
		return 'empty(' . $this->p($node->expr) . ')';
	}

	public function printIssetExpression(IssetExpression $node) {
		return 'isset(' . $this->pCommaSeparated($node->vars) . ')';
	}

	public function printPrintExpression(\PHPParser\Node\Expression\PrintExpression $node) {
		return 'print ' . $this->p($node->expr);
	}

	public function printEvalExpression(EvalExpression $node) {
		return 'eval(' . $this->p($node->expr) . ')';
	}

	public function printIncludeExpression(IncludeExpression $node) {
		static $map = array(
				IncludeExpression::TYPE_INCLUDE      => 'include',
				IncludeExpression::TYPE_INCLUDE_ONCE => 'include_once',
				IncludeExpression::TYPE_REQUIRE      => 'require',
				IncludeExpression::TYPE_REQUIRE_ONCE => 'require_once',
		);

		return $map[$node->type] . ' ' . $this->p($node->expr);
	}

	public function printListExpression(ListExpression $node) {
		$pList = array();
		foreach ($node->vars as $var) {
			if (null === $var) {
				$pList[] = '';
			} else {
				$pList[] = $this->p($var);
			}
		}

		return 'list(' . implode(', ', $pList) . ')';
	}

	// Other

	public function printVariableExpression(VariableExpression $node) {
		if ($node->name instanceof \PHPParser\Node\Expression\Expression) {
			return '${' . $this->p($node->name) . '}';
		} else {
			return '$' . $node->name;
		}
	}

	public function printArrayExpression(ArrayExpression $node) {
		return 'array(' . $this->pCommaSeparated($node->items) . ')';
	}

	public function printArrayItemExpression(ArrayItemExpression $node) {
		return (null !== $node->key ? $this->p($node->key) . ' => ' : '')
		. ($node->byRef ? '&' : '') . $this->p($node->value);
	}

	public function printArrayDimFetchExpression(ArrayDimFetchExpression $node) {
		return $this->pVarOrNewExpr($node->var)
		. '[' . (null !== $node->dim ? $this->p($node->dim) : '') . ']';
	}

	public function printConstFetchExpression(ConstFetchExpression $node) {
		return $this->p($node->name);
	}

	public function printClassConstFetchExpression(ClassConstFetchExpression $node) {
		return $this->p($node->class) . '::' . $node->name;
	}

	public function printPropertyStatementFetchExpression(PropertyStatementFetch $node) {
		return $this->pVarOrNewExpr($node->var) . '->' . $this->printObjectPropertyStatement($node->name);
	}

	public function printStaticPropertyFetchExpression(StaticPropertyFetchExpression $node) {
		return $this->p($node->class) . '::$' . $this->printObjectPropertyStatement($node->name);
	}

	public function printShellExecExpression(ShellExecExpression $node) {
		return '`' . $this->pEncapsList($node->parts, '`') . '`';
	}

	public function printClosureExpression(\PHPParser\Node\Expression\ClosureExpression $node) {
		return ($node->static ? 'static ' : '')
		. 'function ' . ($node->byRef ? '&' : '')
		. '(' . $this->pCommaSeparated($node->params) . ')'
		. (!empty($node->uses) ? ' use(' . $this->pCommaSeparated($node->uses) . ')': '')
		. ' {' . "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printClosureUseExpression(ClosureUseExpression $node) {
		return ($node->byRef ? '&' : '') . '$' . $node->var;
	}

	public function printNewExpression(NewExpression $node) {
		return 'new ' . $this->p($node->class) . '(' . $this->pCommaSeparated($node->args) . ')';
	}

	public function printCloneExpression(CloneExpression $node) {
		return 'clone ' . $this->p($node->expr);
	}

	public function printTernaryExpression(\PHPParser\Node\Expression\TernaryExpression $node) {
		// a bit of cheating: we treat the ternary as a binary op where the ?...: part is the operator.
		// this is okay because the part between ? and : never needs parentheses.
		return $this->pInfixOp('TernaryExpression',
				$node->cond, ' ?' . (null !== $node->if ? ' ' . $this->p($node->if) . ' ' : '') . ': ', $node->else
			);
	}

	public function printExitExpression(ExitExpression $node) {
		return 'die' . (null !== $node->expr ? '(' . $this->p($node->expr) . ')' : '');
	}

	public function printYieldExpression(Yield $node) {
		if ($node->value === null) {
			return 'yield';
		} else {
			// this is a bit ugly, but currently there is no way to detect whether the parentheses are necessary
			return '(yield '
			. ($node->key !== null ? $this->p($node->key) . ' => ' : '')
			. $this->p($node->value)
			. ')';
		}
	}

	// Declarations

	public function printNamespaceStatement(\PHPParser\Node\Statement\NamespaceStatement $node) {
		if ($this->canUseSemicolonNamespaces) {
			return 'namespace ' . $this->p($node->name) . ';' . "\n\n" . $this->pStatements($node->Statements, false);
		} else {
			return 'namespace' . (null !== $node->name ? ' ' . $this->p($node->name) : '')
			. ' {' . "\n" . $this->pStatements($node->Statements) . "\n" . '}';
		}
	}

	public function printUseStatement(\PHPParser\Node\Statement\UseStatement $node) {
		return 'use ' . $this->pCommaSeparated($node->uses) . ';';
	}

	public function printUseUseStatement(\PHPParser\Node\Statement\UseUseStatement $node) {
		return $this->p($node->name)
		. ($node->name->getLast() !== $node->alias ? ' as ' . $node->alias : '');
	}

	public function printInterfaceStatement(\PHPParser\Node\Statement\InterfaceStatement $node) {
		return 'interface ' . $node->name
		. (!empty($node->extends) ? ' extends ' . $this->pCommaSeparated($node->extends) : '')
		. "\n" . '{' . "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printClassStatement(\PHPParser\Node\Statement\ClassStatement $node) {
		return $this->printModifiers($node->type)
		. 'class ' . $node->name
		. (null !== $node->extends ? ' extends ' . $this->p($node->extends) : '')
		. (!empty($node->implements) ? ' implements ' . $this->pCommaSeparated($node->implements) : '')
		. "\n" . '{' . "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printTraitStatement(\PHPParser\Node\Statement\TraitStatement $node) {
		return 'trait ' . $node->name
		. "\n" . '{' . "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printTraitUseStatement(\PHPParser\Node\Statement\TraitUseStatement $node) {
		return 'use ' . $this->pCommaSeparated($node->traits)
		. (empty($node->adaptations)
				? ';'
				: ' {' . "\n" . $this->pStatements($node->adaptations) . "\n" . '}');
	}

	public function printTraitStatementUseAdaptation_Precedence(\PHPParser\Node\Statement\TraitStatementUseAdaptation_Precedence $node) {
		return $this->p($node->trait) . '::' . $node->method
		. ' insteadof ' . $this->pCommaSeparated($node->insteadof) . ';';
	}

	public function printTraitStatementUseAdaptation_Alias(\PHPParser\Node\Statement\TraitStatementUseAdaptation_Alias $node) {
		return (null !== $node->trait ? $this->p($node->trait) . '::' : '')
		. $node->method . ' as'
		. (null !== $node->newModifier ? ' ' . $this->printModifiers($node->newModifier) : '')
		. (null !== $node->newName     ? ' ' . $node->newName                        : '')
		. ';';
	}

	public function printPropertyStatement(\PHPParser\Node\Statement\PropertyStatement $node) {
		return $this->printModifiers($node->type) . $this->pCommaSeparated($node->props) . ';';
	}

	public function printPropertyPropertyStatement(\PHPParser\Node\Statement\PropertyPropertyStatement $node) {
		return '$' . $node->name
		. (null !== $node->default ? ' = ' . $this->p($node->default) : '');
	}

	public function printClassMethodStatement(\PHPParser\Node\Statement\ClassMethodStatement $node) {
		return $this->printModifiers($node->type)
		. 'function ' . ($node->byRef ? '&' : '') . $node->name
		. '(' . $this->pCommaSeparated($node->params) . ')'
		. (null !== $node->Statements
				? "\n" . '{' . "\n" . $this->pStatements($node->Statements) . "\n" . '}'
				: ';');
	}

	public function printClassConstStatement(\PHPParser\Node\Statement\ClassConstStatement $node) {
		return 'const ' . $this->pCommaSeparated($node->consts) . ';';
	}

	public function printFunctionStatement(\PHPParser\Node\Statement\FunctionStatement $node) {
		return 'function ' . ($node->byRef ? '&' : '') . $node->name
		. '(' . $this->pCommaSeparated($node->params) . ')'
		. "\n" . '{' . "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printConstStatement(\PHPParser\Node\Statement\ConstStatement $node) {
		return 'const ' . $this->pCommaSeparated($node->consts) . ';';
	}

	public function printDeclareStatement(\PHPParser\Node\Statement\DeclareStatement $node) {
		return 'declare (' . $this->pCommaSeparated($node->declares) . ') {'
		. "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printDeclareDeclareStatement(\PHPParser\Node\Statement\DeclareDeclareStatement $node) {
		return $node->key . ' = ' . $this->p($node->value);
	}

	// Control flow

	public function printIfStatement(\PHPParser\Node\Statement\IfStatement $node) {
		return 'if (' . $this->p($node->cond) . ') {'
		. "\n" . $this->pStatements($node->Statements) . "\n" . '}'
		. $this->pImplode($node->elseifs)
		. (null !== $node->else ? $this->p($node->else) : '');
	}

	public function printElseIfStatement(\PHPParser\Node\Statement\ElseIfStatement $node) {
		return ' elseif (' . $this->p($node->cond) . ') {'
		. "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printElseStatement(\PHPParser\Node\Statement\ElseStatement $node) {
		return ' else {' . "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printForStatement(\PHPParser\Node\Statement\ForStatement $node) {
		return 'for ('
		. $this->pCommaSeparated($node->init) . ';' . (!empty($node->cond) ? ' ' : '')
		. $this->pCommaSeparated($node->cond) . ';' . (!empty($node->loop) ? ' ' : '')
		. $this->pCommaSeparated($node->loop)
		. ') {' . "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printForeachStatement(\PHPParser\Node\Statement\ForeachStatement $node) {
		return 'foreach (' . $this->p($node->expr) . ' as '
		. (null !== $node->keyVar ? $this->p($node->keyVar) . ' => ' : '')
		. ($node->byRef ? '&' : '') . $this->p($node->valueVar) . ') {'
		. "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printWhileStatement(\PHPParser\Node\Statement\WhileStatement $node) {
		return 'while (' . $this->p($node->cond) . ') {'
		. "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printDoStatement(\PHPParser\Node\Statement\DoStatement $node) {
		return 'do {' . "\n" . $this->pStatements($node->Statements) . "\n"
		. '} while (' . $this->p($node->cond) . ');';
	}

	public function printSwitchStatement(\PHPParser\Node\Statement\SwitchStatement $node) {
		return 'switch (' . $this->p($node->cond) . ') {'
		. "\n" . $this->pStatements($node->cases) . "\n" . '}';
	}

	public function printCaseStatement(\PHPParser\Node\Statement\CaseStatement $node) {
		return (null !== $node->cond ? 'case ' . $this->p($node->cond) : 'default') . ':'
		. ($node->Statements ? "\n" . $this->pStatements($node->Statements) : '');
	}

	public function printTryCatchStatement(TryCatchStatement $node) {
		return 'try {' . "\n" . $this->pStatements($node->Statements) . "\n" . '}'
		. $this->pImplode($node->catches)
		. ($node->finallyStatements !== null
				? ' finally {' . "\n" . $this->pStatements($node->finallyStatements) . "\n" . '}'
				: '');
	}

	public function printCatchStatement(CatchStatement $node) {
		return ' catch (' . $this->p($node->type) . ' $' . $node->var . ') {'
		. "\n" . $this->pStatements($node->Statements) . "\n" . '}';
	}

	public function printBreakStatement(\PHPParser\Node\Statement\BreakStatement $node) {
		return 'break' . ($node->num !== null ? ' ' . $this->p($node->num) : '') . ';';
	}

	public function printContinueStatement(\PHPParser\Node\Statement\ContinueStatement $node) {
		return 'continue' . ($node->num !== null ? ' ' . $this->p($node->num) : '') . ';';
	}

	public function printReturnStatement(\PHPParser\Node\Statement\ReturnStatement $node) {
		return 'return' . (null !== $node->expr ? ' ' . $this->p($node->expr) : '') . ';';
	}

	public function printThrowStatement(\PHPParser\Node\Statement\ThrowStatement $node) {
		return 'throw ' . $this->p($node->expr) . ';';
	}

	public function printLabelStatement(\PHPParser\Node\Statement\LabelStatement $node) {
		return $node->name . ':';
	}

	public function printGotoStatement(\PHPParser\Node\Statement\GotoStatement $node) {
		return 'goto ' . $node->name . ';';
	}

	// Other

	public function printEchoStatement(\PHPParser\Node\Statement\EchoStatement $node) {
		return 'echo ' . $this->pCommaSeparated($node->exprs) . ';';
	}

	public function printStaticStatement(\PHPParser\Node\Statement\StaticStatement $node) {
		return 'static ' . $this->pCommaSeparated($node->vars) . ';';
	}

	public function printGlobalStatement(\PHPParser\Node\Statement\GlobalStatement $node) {
		return 'global ' . $this->pCommaSeparated($node->vars) . ';';
	}

	public function printStaticVarStatement(\PHPParser\Node\Statement\StaticVarStatement $node) {
		return '$' . $node->name
		. (null !== $node->default ? ' = ' . $this->p($node->default) : '');
	}

	public function printUnsetStatement(\PHPParser\Node\Statement\UnsetStatement $node) {
		return 'unset(' . $this->pCommaSeparated($node->vars) . ');';
	}

	public function printInlineHTMLStatement(\PHPParser\Node\Statement\InlineHTMLStatement $node) {
		return '?>' . $this->pNoIndent("\n" . $node->value) . '<?php ';
	}

	public function printHaltCompilerStatement(\PHPParser\Node\Statement\HaltCompilerStatement $node) {
		return '__halt_compiler();' . $node->remaining;
	}

	// Helpers

	public function printObjectPropertyStatement($node) {
		if ($node instanceof \PHPParser\Node\Expression\Expression) {
			return '{' . $this->p($node) . '}';
		} else {
			return $node;
		}
	}

	public function printModifiers($modifiers) {
		return ($modifiers & \PHPParser\Node\Statement\ClassStatement::MODIFIER_PUBLIC    ? 'public '    : '')
		. ($modifiers & \PHPParser\Node\Statement\ClassStatement::MODIFIER_PROTECTED ? 'protected ' : '')
		. ($modifiers & \PHPParser\Node\Statement\ClassStatement::MODIFIER_PRIVATE   ? 'private '   : '')
		. ($modifiers & \PHPParser\Node\Statement\ClassStatement::MODIFIER_STATIC    ? 'static '    : '')
		. ($modifiers & \PHPParser\Node\Statement\ClassStatement::MODIFIER_ABSTRACT  ? 'abstract '  : '')
		. ($modifiers & \PHPParser\Node\Statement\ClassStatement::MODIFIER_FINAL     ? 'final '     : '');
	}

	public function printEncapsList(array $encapsList, $quote) {
		$return = '';
		foreach ($encapsList as $element) {
			if (is_string($element)) {
				$return .= addcslashes($element, "\n\r\t\f\v$" . $quote . "\\");
			} else {
				$return .= '{' . $this->p($element) . '}';
			}
		}

		return $return;
	}

	public function printVarOrNewExpr(\PHPParser\Node $node) {
		if ($node instanceof \PHPParser\Node\Expression\NewExpression) {
			return '(' . $this->p($node) . ')';
		} else {
			return $this->p($node);
		}
	}
}