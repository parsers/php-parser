<?php

namespace PHPParser\Builder;

interface BuilderInterface
{
    /**
     * Returns the built node.
     *
     * @return \PHPParser\Node The built node
     */
    public function getNode();
}
