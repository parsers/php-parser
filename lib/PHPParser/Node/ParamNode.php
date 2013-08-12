<?php

namespace PHPParser\Node;

/**
 * @property string                          $name    Name
 * @property null|\PHPParser\Node\Expr        $default Default value
 * @property null|string|\PHPParser\Node\NameNode $type    Typehint
 * @property bool                            $byRef   Whether is passed by reference
 */
class ParamNode extends NodeAbstract
{
    /**
     * Constructs a parameter node.
     *
     * @param string                          $name       Name
     * @param null|\PHPParser\Node\Expr        $default    Default value
     * @param null|string|\PHPParser\Node\NameNode $type       Typehint
     * @param bool                            $byRef      Whether is passed by reference
     * @param array                           $attributes Additional attributes
     */
    public function __construct($name, $default = null, $type = null, $byRef = false, array $attributes = array()) {
        parent::__construct(
            array(
                'name'    => $name,
                'default' => $default,
                'type'    => $type,
                'byRef'   => $byRef
            ),
            $attributes
        );
    }
}