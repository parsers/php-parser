<?php

namespace PHPParserTest\Comment;

use \PHPParser\Comment\Comment;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSet() {
        $comment = new Comment('/* Some comment */', 1);

        $this->assertEquals('/* Some comment */', $comment->getText());
        $this->assertEquals('/* Some comment */', (string) $comment);
        $this->assertEquals(1, $comment->getLine());

        $comment->setText('/* Some other comment */');
        $comment->setLine(10);

        $this->assertEquals('/* Some other comment */', $comment->getText());
        $this->assertEquals('/* Some other comment */', (string) $comment);
        $this->assertEquals(10, $comment->getLine());
    }

    /**
     * @dataProvider provideTestReformatting
     */
    public function testReformatting($commentText, $reformattedText) {
        $comment = new Comment($commentText);
        $this->assertEquals($reformattedText, $comment->getReformattedText());
    }

    public function provideTestReformatting() {
        return array(
array(
// Unformatted
'// Some text' . "\n",
// Formatted
'// Some text'
),

array(
// Unformatted
'/* Some text */',
// Formatted
'/* Some text */'
),

array(
// Unformatted
'/**
     * Some text.
     * Some more text.
     */',
// Formatted
'/**
 * Some text.
 * Some more text.
 */'
),

array(
// Unformatted
'/*
        Some text.
        Some more text.
    */',
// Formatted
'/*
    Some text.
    Some more text.
*/'
),

array(
// Unformatted
'/* Some text.
       More text.
       Even more text. */',
// Formatted
'/* Some text.
   More text.
   Even more text. */'
),

            // invalid comment -> no reformatting
            array(
// Unformatted
'hallo
    world',
// Formatted
'hallo
    world',
),
        );
    }
}
