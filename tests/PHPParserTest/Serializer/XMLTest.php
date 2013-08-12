<?php

namespace PHPParserTest\Serializer;

use \PHPParser\Serializer\XML;

use \PHPParser\Lexer\Lexer;

use \PHPParser\Parser\Parser;

class XMLTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \PHPParser\Serializer_XML<extended>
     */
    public function testSerialize() {
        $code = <<<CODE
<?php
// comment
/** doc comment */
function functionName(&\$a = 0, \$b = 1.0) {
    echo 'Foo';
}
CODE;
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<AST xmlns:node="http://nikic.github.com/PHPParser/XML/node" xmlns:subNode="http://nikic.github.com/PHPParser/XML/subNode" xmlns:attribute="http://nikic.github.com/PHPParser/XML/attribute" xmlns:scalar="http://nikic.github.com/PHPParser/XML/scalar">
 <scalar:array>
  <node:PHPParser_Node_Statement_FunctionStatement>
   <attribute:comments>
    <scalar:array>
     <comment isDocComment="false" line="2">// comment
</comment>
     <comment isDocComment="true" line="3">/** doc comment */</comment>
    </scalar:array>
   </attribute:comments>
   <attribute:startLine>
    <scalar:int>4</scalar:int>
   </attribute:startLine>
   <attribute:endLine>
    <scalar:int>6</scalar:int>
   </attribute:endLine>
   <subNode:byRef>
    <scalar:false/>
   </subNode:byRef>
   <subNode:params>
    <scalar:array>
     <node:PHPParser_Node_ParamNode>
      <attribute:startLine>
       <scalar:int>4</scalar:int>
      </attribute:startLine>
      <attribute:endLine>
       <scalar:int>4</scalar:int>
      </attribute:endLine>
      <subNode:name>
       <scalar:string>a</scalar:string>
      </subNode:name>
      <subNode:default>
       <node:PHPParser_Node_Scalar_LNumberScalar>
        <attribute:startLine>
         <scalar:int>4</scalar:int>
        </attribute:startLine>
        <attribute:endLine>
         <scalar:int>4</scalar:int>
        </attribute:endLine>
        <subNode:value>
         <scalar:int>0</scalar:int>
        </subNode:value>
       </node:PHPParser_Node_Scalar_LNumberScalar>
      </subNode:default>
      <subNode:type>
       <scalar:null/>
      </subNode:type>
      <subNode:byRef>
       <scalar:true/>
      </subNode:byRef>
     </node:PHPParser_Node_ParamNode>
     <node:PHPParser_Node_ParamNode>
      <attribute:startLine>
       <scalar:int>4</scalar:int>
      </attribute:startLine>
      <attribute:endLine>
       <scalar:int>4</scalar:int>
      </attribute:endLine>
      <subNode:name>
       <scalar:string>b</scalar:string>
      </subNode:name>
      <subNode:default>
       <node:PHPParser_Node_Scalar_DNumberScalar>
        <attribute:startLine>
         <scalar:int>4</scalar:int>
        </attribute:startLine>
        <attribute:endLine>
         <scalar:int>4</scalar:int>
        </attribute:endLine>
        <subNode:value>
         <scalar:float>1</scalar:float>
        </subNode:value>
       </node:PHPParser_Node_Scalar_DNumberScalar>
      </subNode:default>
      <subNode:type>
       <scalar:null/>
      </subNode:type>
      <subNode:byRef>
       <scalar:false/>
      </subNode:byRef>
     </node:PHPParser_Node_ParamNode>
    </scalar:array>
   </subNode:params>
   <subNode:Statements>
    <scalar:array>
     <node:PHPParser_Node_Statement_EchoStatement>
      <attribute:startLine>
       <scalar:int>5</scalar:int>
      </attribute:startLine>
      <attribute:endLine>
       <scalar:int>5</scalar:int>
      </attribute:endLine>
      <subNode:exprs>
       <scalar:array>
        <node:PHPParser_Node_Scalar_StringScalar>
         <attribute:startLine>
          <scalar:int>5</scalar:int>
         </attribute:startLine>
         <attribute:endLine>
          <scalar:int>5</scalar:int>
         </attribute:endLine>
         <subNode:value>
          <scalar:string>Foo</scalar:string>
         </subNode:value>
        </node:PHPParser_Node_Scalar_StringScalar>
       </scalar:array>
      </subNode:exprs>
     </node:PHPParser_Node_Statement_EchoStatement>
    </scalar:array>
   </subNode:Statements>
   <subNode:name>
    <scalar:string>functionName</scalar:string>
   </subNode:name>
  </node:PHPParser_Node_Statement_FunctionStatement>
 </scalar:array>
</AST>
XML;

        $parser     = new Parser(new Lexer);
        $serializer = new XML;

        $Statements = $parser->parse($code);
        $this->assertXmlStringEqualsXmlString($xml, $serializer->serialize($Statements));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Unexpected node type
     */
    public function testError() {
        $serializer = new XML;
        $serializer->serialize(array(new \stdClass));
    }
}
