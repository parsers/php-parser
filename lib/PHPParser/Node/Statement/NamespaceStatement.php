<?php

namespace PHPParser\Node\Statement;

/**
 * @property null|\PHPParser\Node\NameNode $name  Name
 * @property \PHPParser\Node[]         $Statements Statements
 */
use PHPParser\Error\Error;

use PHPParser\Node\NameNode;

class NamespaceStatement extends \PHPParser\Node\Statement\Statement
{
    protected static $specialNames = array(
        'self'   => true,
        'parent' => true,
        'static' => true,
    );

    /**
     * Constructs a namespace node.
     *
     * @param null|\PHPParser\Node\NameNode $name       Name
     * @param \PHPParser\Node[]         $Statements      Statements
     * @param array                    $attributes Additional attributes
     */
    public function __construct(NameNode $name = null, $Statements = array(), array $attributes = array()) {
        parent::__construct(
            array(
                'name'  => $name,
                'Statements' => $Statements,
            ),
            $attributes
        );

        if (isset(self::$specialNames[(string) $this->name])) {
            throw new Error(sprintf('Cannot use "%s" as namespace name as it is reserved', $this->name), $attributes['startLine']);
        }

        if (null !== $this->Statements) {
            foreach ($this->Statements as $Statement) {
                if ($Statement instanceof \PHPParser\Node\Statement\NamespaceStatement) {
                    throw new Error('Namespace declarations cannot be nested', $Statement->getLine());
                }
            }
        }
    }

    public static function postprocess(array $Statements) {
        // null = not in namespace, false = semicolon style, true = bracket style
        $bracketed = null;

        // whether any statements that aren't allowed before a namespace declaration are encountered
        // (the only valid statement currently is a declare)
        $hasNotAllowedStatements = false;

        // offsets for semicolon style namespaces
        // (required for transplanting the following statements into their ->Statements property)
        $nsOffsets = array();

        foreach ($Statements as $i => $Statement) {
            if ($Statement instanceof \PHPParser\Node\Statement\NamespaceStatement) {
                // ->Statements is null if semicolon style is used
                $currentBracketed = null !== $Statement->Statements;

                // if no namespace statement has been encountered yet
                if (!isset($bracketed)) {
                    // set the namespacing style
                    $bracketed = $currentBracketed;

                    // and ensure that it isn't preceded by a not allowed statement
                    if ($hasNotAllowedStatements) {
                        throw new Error('Namespace declaration statement has to be the very first statement in the script', $Statement->getLine());
                    }
                // otherwise ensure that the style of the current namespace matches the style of
                // namespaceing used before in this document
                } elseif ($bracketed !== $currentBracketed) {
                    throw new Error('Cannot mix bracketed namespace declarations with unbracketed namespace declarations', $Statement->getLine());
                }

                // for semicolon style namespaces remember the offset
                if (!$bracketed) {
                    $nsOffsets[] = $i;
                }
            // declare() and __halt_compiler() are the only valid statements outside of namespace declarations
            } elseif (!$Statement instanceof DeclareStatement
                      && !$Statement instanceof HaltCompilerStatement
            ) {
                if (true === $bracketed) {
                    throw new Error('No code may exist outside of namespace {}', $Statement->getLine());
                }

                $hasNotAllowedStatements = true;
            }
        }

        // if bracketed namespaces were used or no namespaces were used at all just return the
        // original statements
        if (!isset($bracketed) || true === $bracketed) {
            return $Statements;
        // for semicolon style transplant statements
        } else {
            // take all statements preceding the first namespace
            $newStatements = array_slice($Statements, 0, $nsOffsets[0]);

            // iterate over all following namespaces
            for ($i = 0, $c = count($nsOffsets); $i < $c; ++$i) {
                $newStatements[] = $nsStatement = $Statements[$nsOffsets[$i]];

                // the last namespace takes all statements after it
                if ($c === $i + 1) {
                    $nsStatement->Statements = array_slice($Statements, $nsOffsets[$i] + 1);

                    // if the last statement is __halt_compiler() put it outside the namespace
                    if (end($nsStatement->Statements) instanceof HaltCompilerStatement) {
                        $newStatements[] = array_pop($nsStatement->Statements);
                    }
                // and all the others take all statements between the current and the following one
                } else {
                    $nsStatement->Statements = array_slice($Statements, $nsOffsets[$i] + 1, $nsOffsets[$i + 1] - $nsOffsets[$i] - 1);
                }
            }

            return $newStatements;
        }
    }
}