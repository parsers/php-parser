<?php

namespace PHPParser\Lexer;

use PHPParser\Token\ArrayToken;

use PHPParser\Token\SingleToken;

use \PHPParser\Comment\DocComment;
use \PHPParser\Comment\Doc;
use \PHPParser\Comment\Comment;
use \PHPParser\Error\Error;
use \PHPParser\Parser\Parser;

class Lexer implements \Iterator
{
    protected $sourceCode;
    protected $tokens;
    protected $position;
    protected $line;

    protected $tokenMap;
    protected $dropTokens;

    /**
     * Creates a Lexer.
     */
    public function __construct() {
        // map from internal tokens to \PHPParser tokens
        $this->tokenMap = $this->createTokenMap();

        // map of tokens to drop while lexing (the map is only used for isset lookup,
        // that's why the value is simply set to 1; the value is never actually used.)
        $this->dropTokens = array_fill_keys(array(T_WHITESPACE, T_OPEN_TAG), 1);
    }

    /**
     * Initializes the lexer for lexing the provided source code.
     *
     * @param string $sourceCode The source code to lex
     *
     * @throws \PHPParser\Error on lexing errors (unterminated comment or unexpected character)
     */
    public function startLexing($sourceCode) {
        $this->resetErrors();
        $this->tokens = @token_get_all($sourceCode);
        $this->handleErrors();

        $this->sourceCode = $sourceCode; // keep the code around for __halt_compiler() handling
        $this->position  = -1;
        $this->line =  1;
    }

    protected function resetErrors() {
        // clear error_get_last() by forcing an undefined variable error
        @$undefinedVariable;
    }

    protected function handleErrors() {
        $error = error_get_last();

        if (preg_match(
            '~^Unterminated comment starting line ([0-9]+)$~',
            $error['message'], $matches
        )) {
            throw new Error('Unterminated comment', $matches[1]);
        }

        if (preg_match(
            '~^Unexpected character in input:  \'(.)\' \(ASCII=([0-9]+)\)~s',
            $error['message'], $matches
        )) {
            throw new Error(sprintf(
                'Unexpected character "%s" (ASCII %d)',
                $matches[1], $matches[2]
            ));
        }

        // PHP cuts error message after null byte, so need special case
        if (preg_match('~^Unexpected character in input:  \'$~', $error['message'])) {
            throw new Error('Unexpected null byte');
        }
    }

    /**
     * Fetches the next token.
     *
     * @param mixed $value           Variable to store token content in
     * @param mixed $startAttributes Variable to store start attributes in
     * @param mixed $endAttributes   Variable to store end attributes in
     *
     * @return \PHPParser\Token Token object
     */
public function getNextToken(&$value = null, &$startAttributes = null, &$endAttributes = null) {
        $startAttributes = array();
        $endAttributes   = array();

        while (isset($this->tokens[++$this->position])) {
            $token = $this->tokens[$this->position];

            if (is_string($token)) {
                $startAttributes['startLine'] = $this->line;
                $endAttributes['endLine']     = $this->line;

                // bug in token_get_all
                if ('b"' === $token) {
                    $value = 'b"';
                    return ord('"');
                } else {
                    $value = $token;
                    return ord($token);
                }
            } else {
                $this->line += substr_count($token[1], "\n");

                if (T_COMMENT === $token[0]) {
                    $startAttributes['comments'][] = new \PHPParser\Comment\Comment($token[1], $token[2]);
                } elseif (T_DOC_COMMENT === $token[0]) {
                    $startAttributes['comments'][] = new \PHPParser\Comment\DocComment($token[1], $token[2]);
                } elseif (!isset($this->dropTokens[$token[0]])) {
                    $value = $token[1];
                    $startAttributes['startLine'] = $token[2];
                    $endAttributes['endLine']     = $this->line;

                    return $this->tokenMap[$token[0]];
                }
            }
        }

        $startAttributes['startLine'] = $this->line;

        // 0 is the EOF token
        return 0;
    }

    /**
     * Handles __halt_compiler() by returning the text after it.
     *
     * @return string Remaining text
     */
    public function handleHaltCompiler() {
        // get the length of the text before the T_HALT_COMPILER token
        $textBefore = '';
        for ($i = 0; $i <= $this->position; ++$i) {
            if (is_string($this->tokens[$i])) {
                $textBefore .= $this->tokens[$i];
            } else {
                $textBefore .= $this->tokens[$i][1];
            }
        }

        // text after T_HALT_COMPILER, still including ();
        $textAfter = substr($this->sourceCode, strlen($textBefore));

        // ensure that it is followed by ();
        // this simplifies the situation, by not allowing any comments
        // in between of the tokens.
        if (!preg_match('~\s*\(\s*\)\s*(?:;|\?>\r?\n?)~', $textAfter, $matches)) {
            throw new Error('__halt_compiler must be followed by "();"', $this->line);
        }

        // prevent the lexer from returning any further tokens
        $this->position = count($this->tokens);

        // return with (); removed
        return (string) substr($textAfter, strlen($matches[0])); // (string) converts false to ''
    }

    /**
     * Creates the token map.
     *
     * The token map maps the PHP internal token identifiers
     * to the identifiers used by the Parser. Additionally it
     * maps T_OPEN_TAG_WITH_ECHO to T_ECHO and T_CLOSE_TAG to ';'.
     *
     * @return array The token map
     */
    protected function createTokenMap() {
        $tokenMap = array();

        // 256 is the minimum possible token number, as everything below
        // it is an ASCII value
        for ($i = 256; $i < 1000; ++$i) {
            // T_DOUBLE_COLON is equivalent to T_PAAMAYIM_NEKUDOTAYIM
            if (T_DOUBLE_COLON === $i) {
                $tokenMap[$i] = Parser::T_PAAMAYIM_NEKUDOTAYIM;
            // T_OPEN_TAG_WITH_ECHO with dropped T_OPEN_TAG results in T_ECHO
            } elseif(T_OPEN_TAG_WITH_ECHO === $i) {
                $tokenMap[$i] = Parser::T_ECHO;
            // T_CLOSE_TAG is equivalent to ';'
            } elseif(T_CLOSE_TAG === $i) {
                $tokenMap[$i] = ord(';');
            // and the others can be mapped directly
            } elseif ('UNKNOWN' !== ($name = token_name($i))
                      && defined($name = '\PHPParser\Parser\Parser::' . $name)
            ) {
                $tokenMap[$i] = constant($name);
            }
        }

        return $tokenMap;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
    	$token = $this->tokens[$this->position];
    	$token[0] = token_name($token[0]);
        return $token;
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->tokens[$this->position]);
    }
}