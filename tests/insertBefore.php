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
 
class SimpleDOM_TestCase_insertBefore extends PHPUnit_Framework_TestCase
{
	public function testBeforeFirstChild()
	{
		$root = new SimpleDOM('<root><child /></root>');
		$new = new SimpleDOM('<new />');

		$return = $root->insertBefore($new, $root->child);

		$this->assertXmlStringEqualsXmlString('<root><new /><child /></root>', $root->asXML());
		$this->assertSame(
			dom_import_simplexml($root->new),
			dom_import_simplexml($return)
		);
	}

	public function testBeforeLastChild()
	{
		$root = new SimpleDOM('<root><child /><otherchild /></root>');
		$new = new SimpleDOM('<new />');

		$return = $root->insertBefore($new, $root->otherchild);

		$this->assertXmlStringEqualsXmlString('<root><child /><new /><otherchild /></root>', $root->asXML());
		$this->assertSame(
			dom_import_simplexml($root->new),
			dom_import_simplexml($return)
		);
	}

	/**
	* @expectedException DOMException
	*/
	public function testNotFound()
	{
		$root = new SimpleDOM('<root><child><grandchild /></child></root>');
		$new = new SimpleDOM('<new />');

		try
		{
			$root->insertBefore($new, $root->child->grandchild);
		}
		catch (DOMException $e)
		{
			$this->assertSame(DOM_NOT_FOUND_ERR, $e->code);
			throw $e;
		}
	}

	/**
	* @expectedException DOMException
	*/
	public function testWrongDocument()
	{
		$root = new SimpleDOM('<root><child><grandchild /></child></root>');
		$new = new SimpleDOM('<new />');
		$node = new SimpleDOM('<node />');

		try
		{
			$root->insertBefore($new, $node);
		}
		catch (DOMException $e)
		{
			$this->assertSame(DOM_NOT_FOUND_ERR, $e->code);
			throw $e;
		}
	}

	public function testNoRef()
	{
		$root = new SimpleDOM('<root><child /></root>');
		$new = new SimpleDOM('<new />');

		$return = $root->insertBefore($new);

		$this->assertXmlStringEqualsXmlString('<root><child /><new /></root>', $root->asXML());
		$this->assertSame(
			dom_import_simplexml($root->new),
			dom_import_simplexml($return)
		);
	}
}