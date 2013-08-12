<?php

namespace PHPParserTest\Node;

use PHPParser\Node\Scalar\StringScalar;
use \PHPParser\Node\Expression\PrintExpression;
use \PHPParser\Node\Traverser;
use \PHPParser\Node\Statement\EchoStatement;
use \PHPParser\Node\Scalar\String;

class TraverserTest extends \PHPUnit_Framework_TestCase
{
    public function testNonModifying() {
        $str1Node = new StringScalar('Foo');
        $str2Node = new StringScalar('Bar');
        $echoNode = new EchoStatement(array($str1Node, $str2Node));
        $Statements    = array($echoNode);

        $visitor = $this->getMock('\PHPParser\Node\Visitor');

        $visitor->expects($this->at(0))->method('beforeTraverse')->with($Statements);
        $visitor->expects($this->at(1))->method('enterNode'     )->with($echoNode);
        $visitor->expects($this->at(2))->method('enterNode'     )->with($str1Node);
        $visitor->expects($this->at(3))->method('leaveNode'     )->with($str1Node);
        $visitor->expects($this->at(4))->method('enterNode'     )->with($str2Node);
        $visitor->expects($this->at(5))->method('leaveNode'     )->with($str2Node);
        $visitor->expects($this->at(6))->method('leaveNode'     )->with($echoNode);
        $visitor->expects($this->at(7))->method('afterTraverse' )->with($Statements);

        $traverser = new Traverser;
        $traverser->addVisitor($visitor);

        $this->assertEquals($Statements, $traverser->traverse($Statements));
    }

    public function testModifying() {
        $str1Node  = new StringScalar('Foo');
        $str2Node  = new StringScalar('Bar');
        $printNode = new \PHPParser\Node\Expression\PrintExpression($str1Node);

        // first visitor changes the node, second verifies the change
        $visitor1 = $this->getMock('\PHPParser\Node\Visitor');
        $visitor2 = $this->getMock('\PHPParser\Node\Visitor');

        // replace empty statements with string1 node
        $visitor1->expects($this->at(0))->method('beforeTraverse')->with(array())
                 ->will($this->returnValue(array($str1Node)));
        $visitor2->expects($this->at(0))->method('beforeTraverse')->with(array($str1Node));

        // replace string1 node with print node
        $visitor1->expects($this->at(1))->method('enterNode')->with($str1Node)
                 ->will($this->returnValue($printNode));
        $visitor2->expects($this->at(1))->method('enterNode')->with($printNode);

        // replace string1 node with string2 node
        $visitor1->expects($this->at(2))->method('enterNode')->with($str1Node)
                 ->will($this->returnValue($str2Node));
        $visitor2->expects($this->at(2))->method('enterNode')->with($str2Node);

        // replace string2 node with string1 node again
        $visitor1->expects($this->at(3))->method('leaveNode')->with($str2Node)
                 ->will($this->returnValue($str1Node));
        $visitor2->expects($this->at(3))->method('leaveNode')->with($str1Node);

        // replace print node with string1 node again
        $visitor1->expects($this->at(4))->method('leaveNode')->with($printNode)
                 ->will($this->returnValue($str1Node));
        $visitor2->expects($this->at(4))->method('leaveNode')->with($str1Node);

        // replace string1 node with empty statements again
        $visitor1->expects($this->at(5))->method('afterTraverse')->with(array($str1Node))
                 ->will($this->returnValue(array()));
        $visitor2->expects($this->at(5))->method('afterTraverse')->with(array());

        $traverser = new Traverser;
        $traverser->addVisitor($visitor1);
        $traverser->addVisitor($visitor2);

        // as all operations are reversed we end where we start
        $this->assertEquals(array(), $traverser->traverse(array()));
    }

    public function testRemove() {
        $str1Node = new StringScalar('Foo');
        $str2Node = new StringScalar('Bar');

        $visitor = $this->getMock('\PHPParser\Node\Visitor');

        // remove the string1 node, leave the string2 node
        $visitor->expects($this->at(2))->method('leaveNode')->with($str1Node)
                ->will($this->returnValue(false));

        $traverser = new Traverser;
        $traverser->addVisitor($visitor);

        $this->assertEquals(array($str2Node), $traverser->traverse(array($str1Node, $str2Node)));
    }

    public function testMerge() {
        $strStart  = new StringScalar('Start');
        $strMiddle = new StringScalar('End');
        $strEnd    = new StringScalar('Middle');
        $strR1     = new StringScalar('Replacement 1');
        $strR2     = new StringScalar('Replacement 2');

        $visitor = $this->getMock('\PHPParser\Node\Visitor');

        // replace strMiddle with strR1 and strR2 by merge
        $visitor->expects($this->at(4))->method('leaveNode')->with($strMiddle)
                ->will($this->returnValue(array($strR1, $strR2)));

        $traverser = new Traverser;
        $traverser->addVisitor($visitor);

        $this->assertEquals(
            array($strStart, $strR1, $strR2, $strEnd),
            $traverser->traverse(array($strStart, $strMiddle, $strEnd))
        );
    }

    public function testDeepArray() {
        $strNode = new StringScalar('Foo');
        $Statements = array(array(array($strNode)));

        $visitor = $this->getMock('\PHPParser\Node\Visitor');
        $visitor->expects($this->at(1))->method('enterNode')->with($strNode);

        $traverser = new Traverser;
        $traverser->addVisitor($visitor);

        $this->assertEquals($Statements, $traverser->traverse($Statements));
    }
}
