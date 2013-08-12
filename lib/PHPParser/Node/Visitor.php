<?php

namespace PHPParser\Node;

interface Visitor
{
    /**
     * Called once before traversal.
     *
     * Return value semantics:
     *  * null:      $nodes stays as-is
     *  * otherwise: $nodes is set to the return value
     *
     * @param \PHPParser\Node[] $nodes Array of nodes
     *
     * @return null|\PHPParser\Node[] Array of nodes
     */
    public function beforeTraverse(array $nodes);

    /**
     * Called when entering a node.
     *
     * Return value semantics:
     *  * null:      $node stays as-is
     *  * otherwise: $node is set to the return value
     *
     * @param \PHPParser\Node $node Node
     *
     * @return null|\PHPParser\Node Node
     */
    public function enterNode(NodeInterface $node);

    /**
     * Called when leaving a node.
     *
     * Return value semantics:
     *  * null:      $node stays as-is
     *  * false:     $node is removed from the parent array
     *  * array:     The return value is merged into the parent array (at the position of the $node)
     *  * otherwise: $node is set to the return value
     *
     * @param \PHPParser\Node $node Node
     *
     * @return null|\PHPParser\Node|false|\PHPParser\Node[] Node
     */
    public function leaveNode(NodeInterface $node);

    /**
     * Called once after traversal.
     *
     * Return value semantics:
     *  * null:      $nodes stays as-is
     *  * otherwise: $nodes is set to the return value
     *
     * @param \PHPParser\Node[] $nodes Array of nodes
     *
     * @return null|\PHPParser\Node[] Array of nodes
     */
    public function afterTraverse(array $nodes);
}