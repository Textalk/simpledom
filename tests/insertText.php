<?php
/*

Copyright 2007 The SimpleDOM Working Group Initiative

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../SimpleDOM.php';
 
class SimpleDOM_TestCase_insertText extends PHPUnit_Framework_TestCase
{
	public function testAppendIsDefault()
	{
		$root		= new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$expected	= '<root><child1 /><child2 /><child3/>TEST</root>';

		$root->insertText('TEST');

		$this->assertXmlStringEqualsXmlString($expected, $root->asXML());
	}

	public function testAppend()
	{
		$root		= new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$expected	= '<root><child1 /><child2 /><child3/>TEST</root>';

		$root->insertText('TEST', 'append');

		$this->assertXmlStringEqualsXmlString($expected, $root->asXML());
	}

	public function testBefore()
	{
		$root		= new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$expected	= '<root>TEST<child1 /><child2 /><child3/></root>';

		$root->child1->insertText('TEST', 'before');

		$this->assertXmlStringEqualsXmlString($expected, $root->asXML());
	}

	public function testAfterWithNextSibling()
	{
		$root		= new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$expected	= '<root><child1 />TEST<child2 /><child3/></root>';

		$root->child1->insertText('TEST', 'after');

		$this->assertXmlStringEqualsXmlString($expected, $root->asXML());
	}

	public function testAfterWithoutNextSibling()
	{
		$root		= new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$expected	= '<root><child1 /><child2 /><child3/>TEST</root>';

		$root->child3->insertText('TEST', 'after');

		$this->assertXmlStringEqualsXmlString($expected, $root->asXML());
	}

	public function testEmptyRoot()
	{
		$root = new SimpleDOM('<root />');

		$return = $root->insertText('foo');

		$this->assertXmlStringEqualsXmlString('<root>foo</root>', $root->asXML());
		$this->assertSame($root, $return);
	}

	public function testNonEmptyRoot()
	{
		$root = new SimpleDOM('<root>foo</root>');

		$return = $root->insertText('bar');

		$this->assertXmlStringEqualsXmlString('<root>foobar</root>', $root->asXML());
		$this->assertSame($root, $return);
	}

	public function testNonEmptyRootWithChild()
	{
		$root = new SimpleDOM('<root>foo<bar /></root>');

		$return = $root->insertText('baz');

		$this->assertXmlStringEqualsXmlString('<root>foo<bar />baz</root>', $root->asXML());
		$this->assertSame($root, $return);
	}

	public function testChild()
	{
		$root = new SimpleDOM('<root><foo /></root>');
		$foo = $root->foo;

		$return = $foo->insertText('bar')->insertText('baz');

		$this->assertXmlStringEqualsXmlString('<root><foo>barbaz</foo></root>', $root->asXML());
		$this->assertSame($foo, $return);
	}
}