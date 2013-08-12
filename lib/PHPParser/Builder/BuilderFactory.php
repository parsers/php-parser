<?php

namespace PHPParser\Builder;

use \PHPParser\Builder\InterfaceBuilder;
use \PHPParser\Builder\PropertyBuilder;
use \PHPParser\Builder\ParamBuilder;

/**
 * "class", "interface" and "function" are reserved keywords, so the methods are defined as _class(),
 * _interface() and _function() in the class and are made available as class(), interface() and function()
 * through __call() magic.
 *
 * @method \PHPParser\ClassBuilder     class(string $name)     Creates a class builder.
 * @method \PHPParser\FunctionBuilder  function(string $name)  Creates a function builder
 * @method \PHPParser\InterfaceBuilder interface(string $name) Creates an interface builder.
 */
class BuilderFactory
{
    /**
     * Creates a class builder.
     *
     * @param string $name Name of the class
     *
     * @return \PHPParser\ClassBuilder The created class builder
     */
    protected function _class($name) {
        return new ClassBuilder($name);
    }

    /**
     * Creates a interface builder.
     *
     * @param string $name Name of the interface
     *
     * @return \PHPParser\ClassBuilder The created interface builder
     */
    protected function _interface($name) {
        return new InterfaceBuilder($name);
    }

    /**
     * Creates a method builder.
     *
     * @param string $name Name of the method
     *
     * @return \PHPParser\MethodBuilder The created method builder
     */
    public function method($name) {
        return new MethodBuilder($name);
    }

    /**
     * Creates a parameter builder.
     *
     * @param string $name Name of the parameter
     *
     * @return \PHPParser\ParamBuilder The created parameter builder
     */
    public function param($name) {
        return new ParamBuilder($name);
    }

    /**
     * Creates a property builder.
     *
     * @param string $name Name of the property
     *
     * @return \PHPParser\PropertyBuilder The created property builder
     */
    public function property($name) {
        return new PropertyBuilder($name);
    }

    /**
     * Creates a function builder.
     *
     * @param string $name Name of the function
     *
     * @return \PHPParser\PropertyBuilder The created function builder
     */
    protected function _function($name) {
        return new FunctionBuilder($name);
    }

    public function __call($name, array $args) {
        if (method_exists($this, '_' . $name)) {
            return call_user_func_array(array($this, '_' . $name), $args);
        }

        throw new \LogicException(sprintf('Method "%s" does not exist', $name));
    }
}