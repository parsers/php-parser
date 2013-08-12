PHP Parser
==========

This is a PHP 5.4 parser written in PHP. It's purpose is to simplify static code analysis and
manipulation.

It is based on the famous nickic/PHP-Parser project

In a Nutshell
-------------

Basically, the parser does nothing more than turn some PHP code into an abstract syntax tree. ("nothing
more" is kind of sarcastic here as PHP has a ... uhm, let's just say "not nice" ... grammar, which makes
parsing PHP very hard.)

For example, if you stick this code in the parser:

```php
<?php
echo 'Hi', 'World';
hello\world('foo', 'bar' . 'baz');
```

You'll get a syntax tree looking roughly like this:

```
array(
    0: PHPParser\Node\Statement\EchoStatement(
        exprs: array(
            0: PHPParser\Node\Scalar\StringScalar(
                value: Hi
            )
            1: PHPParser\Node\Scalar\StringScalar(
                value: World
            )
        )
    )
    1: PHPParser\Node\Expression\FuncCallExpression(
        name: PHPParser\Node\NameNode(
            parts: array(
                0: hello
                1: world
            )
        )
        args: array(
            0: PHPParser\Node\ArgNode(
                value: PHPParser\Node\Scalar\StringScalar(
                    value: foo
                )
                byRef: false
            )
            1: PHPParser\Node\ArgNode(
                value: PHPParser\Node\Expression\ConcatExpression(
                    left: PHPParser\Node\Scalar\StringScalar(
                        value: bar
                    )
                    right: PHPParser\Node\Scalar\StringScalar(
                        value: baz
                    )
                )
                byRef: false
            )
        )
    )
)
```

You can then work with this syntax tree, for example to statically analyze the code (e.g. to find
programming errors or security issues).

Additionally, you can convert a syntax tree back to PHP code. This allows you to do code preprocessing
(like automatedly porting code to older PHP versions).
