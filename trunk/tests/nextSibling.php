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
 
class SimpleDOM_TestCase_nextSibling extends PHPUnit_Framework_TestCase
{
	public function testRoot()
	{
		$root = new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$this->assertNull($root->nextSibling());
	}

	public function testFirstChild()
	{
		$root = new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$child2 = $root->child1->nextSibling();

		$this->assertTrue($child2 instanceof SimpleDOM);
		$this->assertSame(
			dom_import_simplexml($root->child2),
			dom_import_simplexml($child2)
		);
	}

	public function testMiddleChild()
	{
		$root = new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$child3 = $root->child2->nextSibling();

		$this->assertTrue($child3 instanceof SimpleDOM);
		$this->assertSame(
			dom_import_simplexml($root->child3),
			dom_import_simplexml($child3)
		);
	}

	public function testLastChild()
	{
		$root = new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$this->assertNull($root->child3->nextSibling());
	}
}