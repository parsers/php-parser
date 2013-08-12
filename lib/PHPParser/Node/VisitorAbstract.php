<?php

namespace PHPParser\Node;

/**
 * @codeCoverageIgnore
 */
class VisitorAbstract implements Visitor
{
    public function beforeTraverse(array $nodes)    { }
    public function enterNode(NodeInterface $node) { }
    public function leaveNode(NodeInterface $node) { }
    public function afterTraverse(array $nodes)     { }
}