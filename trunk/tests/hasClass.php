<?php
/*

Copyright 2009 The SimpleDOM authors

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
 
class SimpleDOM_TestCase_hasClass extends PHPUnit_Framework_TestCase
{
	public function testMatch()
	{
		$node = new SimpleDom('<node class="foo" />');
		$this->assertTrue($node->hasClass('foo'));
	}

	public function testMatchLeadingClass()
	{
		$node = new SimpleDom('<node class="foo bar baz" />');
		$this->assertTrue($node->hasClass('foo'));
	}

	public function testMatchMiddleClass()
	{
		$node = new SimpleDom('<node class="foo bar baz" />');
		$this->assertTrue($node->hasClass('bar'));
	}

	public function testMatchTrailingClass()
	{
		$node = new SimpleDom('<node class="foo bar baz" />');
		$this->assertTrue($node->hasClass('baz'));
	}

	public function testNoSubstringMatch()
	{
		$node = new SimpleDom('<node class="foobar" />');
		$this->assertFalse($node->hasClass('bar'));
	}

	public function testNoCaseInsensitiveMatch()
	{
		$node = new SimpleDom('<node class="Foo" />');
		$this->assertFalse($node->hasClass('foo'));
	}

	public function testNoMatch()
	{
		$node = new SimpleDom('<node class="foo" />');
		$this->assertFalse($node->hasClass('bar'));
	}

	public function testNoMatchNoClass()
	{
		$node = new SimpleDom('<node />');
		$this->assertFalse($node->hasClass('bar'));
	}

	public function testMatchDoesNotAlterTheNode()
	{
		$node     = new SimpleDom('<node class="foo" />');
		$expected = $node->asXML();

		$node->hasClass('foo');

		$this->assertXmlStringEqualsXmlString($expected, $node->asXML());
	}

	public function testNoMatchDoesNotAlterTheNode()
	{
		$node     = new SimpleDom('<node class="foo" />');
		$expected = $node->asXML();

		$node->hasClass('bar');

		$this->assertXmlStringEqualsXmlString($expected, $node->asXML());
	}

	public function testNoMatchNoClassDoesNotAlterTheNode()
	{
		$node     = new SimpleDom('<node />');
		$expected = $node->asXML();

		$node->hasClass('bar');

		$this->assertXmlStringEqualsXmlString($expected, $node->asXML());
	}
}