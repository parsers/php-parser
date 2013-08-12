<?php

namespace PHPParser\PrettyPrinter;

abstract class PrettyPrinterAbstract
{
    protected $precedenceMap = array(
        // [precedence, associativity] where for the latter -1 is %left, 0 is %nonassoc and 1 is %right
        'BitwiseNotExpression'       => array( 1,  1),
        'PreIncExpression'           => array( 1,  1),
        'PreDecExpression'           => array( 1,  1),
        'PostIncExpression'          => array( 1, -1),
        'PostDecExpression'          => array( 1, -1),
        'UnaryPlusExpression'        => array( 1,  1),
        'UnaryMinusExpression'       => array( 1,  1),
        'Cast_IntExpression'         => array( 1,  1),
        'Cast_DoubleExpression'      => array( 1,  1),
        'Cast_StringExpression'      => array( 1,  1),
        'Cast_ArrayExpression'       => array( 1,  1),
        'Cast_ObjectExpression'      => array( 1,  1),
        'Cast_BoolExpression'        => array( 1,  1),
        'Cast_UnsetExpression'       => array( 1,  1),
        'ErrorSuppressExpression'    => array( 1,  1),
        'InstanceofExpression'       => array( 2,  0),
        'BooleanNotExpression'       => array( 3,  1),
        'MulExpression'              => array( 4, -1),
        'DivExpression'              => array( 4, -1),
        'ModExpression'              => array( 4, -1),
        'PlusExpression'             => array( 5, -1),
        'MinusExpression'            => array( 5, -1),
        'ConcatExpression'           => array( 5, -1),
        'ShiftLeftExpression'        => array( 6, -1),
        'ShiftRightExpression'       => array( 6, -1),
        'SmallerExpression'          => array( 7,  0),
        'SmallerOrEqualExpression'   => array( 7,  0),
        'GreaterExpression'          => array( 7,  0),
        'GreaterOrEqualExpression'   => array( 7,  0),
        'EqualExpression'            => array( 8,  0),
        'NotEqualExpression'         => array( 8,  0),
        'IdenticalExpression'        => array( 8,  0),
        'NotIdenticalExpression'     => array( 8,  0),
        'BitwiseAndExpression'       => array( 9, -1),
        'BitwiseXorExpression'       => array(10, -1),
        'BitwiseOrExpression'        => array(11, -1),
        'BooleanAndExpression'       => array(12, -1),
        'BooleanOrExpression'        => array(13, -1),
        'TernaryExpression'          => array(14, -1),
        // parser uses %left for assignments, but they really behave as %right
        'AssignExpression'           => array(15,  1),
        'AssignRefExpression'        => array(15,  1),
        'AssignPlusExpression'       => array(15,  1),
        'AssignMinusExpression'      => array(15,  1),
        'AssignMulExpression'        => array(15,  1),
        'AssignDivExpression'        => array(15,  1),
        'AssignConcatExpression'     => array(15,  1),
        'AssignModExpression'        => array(15,  1),
        'AssignBitwiseAndExpression' => array(15,  1),
        'AssignBitwiseOrExpression'  => array(15,  1),
        'AssignBitwiseXorExpression' => array(15,  1),
        'AssignShiftLeftExpression'  => array(15,  1),
        'AssignShiftRightExpression' => array(15,  1),
        'LogicalAndExpression'       => array(16, -1),
        'LogicalXorExpression'       => array(17, -1),
        'LogicalOrExpression'        => array(18, -1),
        'IncludeExpression'          => array(19, -1),
    );

    protected $noIndentToken;
    protected $canUseSemicolonNamespaces;

    public function __construct() {
        $this->noIndentToken = '_NO_INDENT_' . mt_rand();
    }

    /**
     * Pretty prints an array of statements.
     *
     * @param \PHPParser\Node[] $Statements Array of statements
     *
     * @return string Pretty printed statements
     */
    public function prettyPrint(array $Statements) {
        $this->preprocessNodes($Statements);

        return str_replace("\n" . $this->noIndentToken, "\n", $this->pStatements($Statements, false));
    }

    /**
     * Pretty prints an expression.
     *
     * @param \PHPParser\Node\Expression\Expression $node Expression node
     *
     * @return string Pretty printed node
     */
    public function prettyPrintExpr(\PHPParser\Node\Expression\Expression $node) {
        return str_replace("\n" . $this->noIndentToken, "\n", $this->p($node));
    }

    /**
     * Pretty prints a file of statements (includes the opening <?php tag if it is required).
     *
     * @param \PHPParser\Node[] $Statements Array of statements
     *
     * @return string Pretty printed statements
     */
    public function prettyPrintFile(array $Statements) {
        $p = trim($this->prettyPrint($Statements));

        $p = preg_replace('/^\?>\n?/', '', $p, -1, $count);
        $p = preg_replace('/<\?php$/', '', $p);

        if (!$count) {
            $p = "<?php\n\n" . $p;
        }

        return $p;
    }

    /**
     * Preprocesses the top-level nodes to initialize pretty printer state.
     *
     * @param \PHPParser\Node[] $nodes Array of nodes
     */
    protected function preprocessNodes(array $nodes) {
        /* We can use semicolon-namespaces unless there is a global namespace declaration */
        $this->canUseSemicolonNamespaces = true;
        foreach ($nodes as $node) {
            if ($node instanceof \PHPParser\Node\Statement\NamespaceStatement && null === $node->name) {
                $this->canUseSemicolonNamespaces = false;
            }
        }
    }

    /**
     * Pretty prints an array of nodes (statements) and indents them optionally.
     *
     * @param \PHPParser\Node[] $nodes  Array of nodes
     * @param bool             $indent Whether to indent the printed nodes
     *
     * @return string Pretty printed statements
     */
    protected function pStatements(array $nodes, $indent = true) {
        $pNodes = array();
        foreach ($nodes as $node) {
            $pNodes[] = $this->pComments($node->getAttribute('comments', array()))
                      . $this->p($node)
                      . ($node instanceof \PHPParser\Node\Expression\Expression ? ';' : '');
        }

        if ($indent) {
            return '    ' . preg_replace(
                '~\n(?!$|' . $this->noIndentToken . ')~',
                "\n" . '    ',
                implode("\n", $pNodes)
            );
        } else {
            return implode("\n", $pNodes);
        }
    }

    /**
     * Pretty prints a node.
     *
     * @param \PHPParser\Node\NodeInterface $node Node to be pretty printed
     *
     * @return string Pretty printed node
     */
    protected function p(\PHPParser\Node\NodeInterface $node) {
    	$func = $node->getType();
    	$func = str_replace('\\', '_', $func);
    	$func = str_replace('PHPParser_Node_Statement_', '', $func);
    	$func = str_replace('PHPParser_Node_Scalar_', '', $func);
    	$func = str_replace('PHPParser_Node_Expression_', '', $func);
    	$func = str_replace('PHPParser_Node_', '', $func);
        return $this->{'print' . $func}($node);
    }

    protected function pInfixOp($type, \PHPParser\Node\NodeInterface $leftNode, $operatorString, \PHPParser\Node\NodeInterface $rightNode) {
        list($precedence, $associativity) = $this->precedenceMap[$type];

        return $this->pPrec($leftNode, $precedence, $associativity, -1)
             . $operatorString
             . $this->pPrec($rightNode, $precedence, $associativity, 1);
    }

    protected function pPrefixOp($type, $operatorString, \PHPParser\Node\NodeInterface $node) {
        list($precedence, $associativity) = $this->precedenceMap[$type];
        return $operatorString . $this->pPrec($node, $precedence, $associativity, 1);
    }

    protected function pPostfixOp($type, \PHPParser\Node\NodeInterface $node, $operatorString) {
        list($precedence, $associativity) = $this->precedenceMap[$type];
        return $this->pPrec($node, $precedence, $associativity, -1) . $operatorString;
    }

    /**
     * Prints an expression node with the least amount of parentheses necessary to preserve the meaning.
     *
     * @param \PHPParser\Node\NodeInterface $node                Node to pretty print
     * @param int            $parentPrecedence    Precedence of the parent operator
     * @param int            $parentAssociativity Associativity of parent operator
     *                                            (-1 is left, 0 is nonassoc, 1 is right)
     * @param int            $childPosition       Position of the node relative to the operator
     *                                            (-1 is left, 1 is right)
     *
     * @return string The pretty printed node
     */
    protected function pPrec(\PHPParser\Node\NodeInterface $node, $parentPrecedence, $parentAssociativity, $childPosition) {
        $type = $node->getType();
        
        $func = $type;
        $func = str_replace('\\', '_', $func);
        $func = str_replace('PHPParser_Node_Statement_', '', $func);
        $func = str_replace('PHPParser_Node_Scalar_', '', $func);
        $func = str_replace('PHPParser_Node_Expression_', '', $func);
        $func = str_replace('PHPParser_Node_', '', $func);
        
        if (isset($this->precedenceMap[$func])) {
            $childPrecedence = $this->precedenceMap[$func][0];
            if ($childPrecedence > $parentPrecedence
                || ($parentPrecedence == $childPrecedence && $parentAssociativity != $childPosition)
            ) {
                return '(' . $this->{'print' . $func}($node) . ')';
            }
        }

        return $this->{'print' . $func}($node);
    }

    /**
     * Pretty prints an array of nodes and implodes the printed values.
     *
     * @param \PHPParser\Node[] $nodes Array of Nodes to be printed
     * @param string           $glue  Character to implode with
     *
     * @return string Imploded pretty printed nodes
     */
    protected function pImplode(array $nodes, $glue = '') {
        $pNodes = array();
        foreach ($nodes as $node) {
            $pNodes[] = $this->p($node);
        }

        return implode($glue, $pNodes);
    }

    /**
     * Pretty prints an array of nodes and implodes the printed values with commas.
     *
     * @param \PHPParser\Node[] $nodes Array of Nodes to be printed
     *
     * @return string Comma separated pretty printed nodes
     */
    protected function pCommaSeparated(array $nodes) {
        return $this->pImplode($nodes, ', ');
    }

    /**
     * Signals the pretty printer that a string shall not be indented.
     *
     * @param string $string Not to be indented string
     *
     * @return mixed String marked with $this->noIndentToken's.
     */
    protected function pNoIndent($string) {
        return str_replace("\n", "\n" . $this->noIndentToken, $string);
    }

    protected function pComments(array $comments) {
        $result = '';

        foreach ($comments as $comment) {
            $result .= $comment->getReformattedText() . "\n";
        }

        return $result;
    }
}