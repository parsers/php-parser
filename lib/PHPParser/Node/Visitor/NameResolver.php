<?php

namespace PHPParser\Node\Visitor;

use PHPParser\Error\Error;

use PHPParser\Node\FullyQualifiedNameNode;

use PHPParser\Node\ParamNode;

use PHPParser\Node\Statement\TraitUseStatement;

use PHPParser\Node\Expression\ConstFetchExpression;

use PHPParser\Node\Expression\FuncCallExpression;

use PHPParser\Node\Statement\CatchStatement;

use PHPParser\Node\Expression\InstanceofExpression;

use PHPParser\Node\Expression\NewExpression;

use PHPParser\Node\Expression\ClassConstFetchExpression;

use PHPParser\Node\Expression\StaticPropertyFetchExpression;

use PHPParser\Node\Expression\StaticCallExpression;

use PHPParser\Node\Statement\ConstStatement;

use PHPParser\Node\Statement\FunctionStatement;

use PHPParser\Node\Statement\InterfaceStatement;

use PHPParser\Node\Statement\ClassStatement;

use PHPParser\Node\Statement\UseUseStatement;

use PHPParser\Node\NameNode;

use PHPParser\Node\NodeInterface;

use \PHPParser\Node\Node;

use \PHPParser\Node\Statement\TraitStatement;

use \PHPParser\Node\VisitorAbstract;

class NameResolver extends VisitorAbstract
{
    /**
     * @var null|\PHPParser\Node\Name Current namespace
     */
    protected $namespace;

    /**
     * @var array Currently defined namespace and class aliases
     */
    protected $aliases;

    public function beforeTraverse(array $nodes) {
        $this->namespace = null;
        $this->aliases   = array();
    }

    public function enterNode(NodeInterface $node) {
        if ($node instanceof \PHPParser\Node\Statement\NamespaceStatement) {
            $this->namespace = $node->name;
            $this->aliases   = array();
        } elseif ($node instanceof UseUseStatement) {
            if (isset($this->aliases[$node->alias])) {
                throw new Error(
                    sprintf(
                        'Cannot use "%s" as "%s" because the name is already in use',
                        $node->name, $node->alias
                    ),
                    $node->getLine()
                );
            }

            $this->aliases[$node->alias] = $node->name;
        } elseif ($node instanceof ClassStatement) {
            if (null !== $node->extends) {
                $node->extends = $this->resolveClassName($node->extends);
            }

            foreach ($node->implements as &$interface) {
                $interface = $this->resolveClassName($interface);
            }

            $this->addNamespacedName($node);
        } elseif ($node instanceof InterfaceStatement) {
            foreach ($node->extends as &$interface) {
                $interface = $this->resolveClassName($interface);
            }

            $this->addNamespacedName($node);
        } elseif ($node instanceof \PHPParser\Node\Statement\TraitStatement) {
            $this->addNamespacedName($node);
        } elseif ($node instanceof FunctionStatement) {
            $this->addNamespacedName($node);
        } elseif ($node instanceof ConstStatement) {
            foreach ($node->consts as $const) {
                $this->addNamespacedName($const);
            }
        } elseif ($node instanceof StaticCallExpression
                  || $node instanceof StaticPropertyFetchExpression
                  || $node instanceof ClassConstFetchExpression
                  || $node instanceof NewExpression
                  || $node instanceof InstanceofExpression
        ) {
            if ($node->class instanceof NameNode) {
                $node->class = $this->resolveClassName($node->class);
            }
        } elseif ($node instanceof CatchStatement) {
            $node->type = $this->resolveClassName($node->type);
        } elseif ($node instanceof FuncCallExpression
                  || $node instanceof ConstFetchExpression
        ) {
            if ($node->name instanceof NameNode) {
                $node->name = $this->resolveOtherName($node->name);
            }
        } elseif ($node instanceof TraitUseStatement) {
            foreach ($node->traits as &$trait) {
                $trait = $this->resolveClassName($trait);
            }
        } elseif ($node instanceof ParamNode
                  && $node->type instanceof NameNode
        ) {
            $node->type = $this->resolveClassName($node->type);
        }
    }

    protected function resolveClassName(NameNode $name) {
        // don't resolve special class names
        if (in_array((string) $name, array('self', 'parent', 'static'))) {
            return $name;
        }

        // fully qualified names are already resolved
        if ($name->isFullyQualified()) {
            return $name;
        }

        // resolve aliases (for non-relative names)
        if (!$name->isRelative() && isset($this->aliases[$name->getFirst()])) {
            $name->setFirst($this->aliases[$name->getFirst()]);
        // if no alias exists prepend current namespace
        } elseif (null !== $this->namespace) {
            $name->prepend($this->namespace);
        }

        return new FullyQualifiedNameNode($name->parts, $name->getAttributes());
    }

    protected function resolveOtherName(NameNode $name) {
        // fully qualified names are already resolved and we can't do anything about unqualified
        // ones at compiler-time
        if ($name->isFullyQualified() || $name->isUnqualified()) {
            return $name;
        }

        // resolve aliases for qualified names
        if ($name->isQualified() && isset($this->aliases[$name->getFirst()])) {
            $name->setFirst($this->aliases[$name->getFirst()]);
        // prepend namespace for relative names
        } elseif (null !== $this->namespace) {
            $name->prepend($this->namespace);
        }

        return new FullyQualifiedNameNode($name->parts, $name->getAttributes());
    }

    protected function addNamespacedName(NodeInterface $node) {
        if (null !== $this->namespace) {
            $node->namespacedName = clone $this->namespace;
            $node->namespacedName->append($node->name);
        } else {
            $node->namespacedName = new NameNode($node->name, $node->getAttributes());
        }
    }
}