<?php

namespace PHPParser\Node;

interface TraverserInterface
{
    /**
     * Adds a visitor.
     *
     * @param \PHPParser\NodeVisitor $visitor Visitor to add
     */
    function addVisitor(Visitor $visitor);

    /**
     * Traverses an array of nodes using the registered visitors.
     *
     * @param \PHPParser\Node[] $nodes Array of nodes
     *
     * @return \PHPParser\Node[] Traversed array of nodes
     */
    function traverse(array $nodes);
}

