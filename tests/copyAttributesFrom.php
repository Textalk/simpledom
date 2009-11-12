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
 
class SimpleDOM_TestCase_copyAttributesFrom extends PHPUnit_Framework_TestCase
{
	public function test()
	{
		$root = new SimpleDOM(
			'<root>
				<child1 />
				<child2 a="aval" b="bval" />
			</root>'
		);

		$root->child1->copyAttributesFrom($root->child2);

		$this->assertXmlStringEqualsXmlString(
			'<root>
				<child1 a="aval" b="bval" />
				<child2 a="aval" b="bval" />
			</root>',
			$root->asXML()
		);
	}

	public function testAttributesAreCopiedAcrossDocuments()
	{
		$root = new SimpleDOM(
			'<root>
				<child1 />
			</root>'
		);

		$other = new SimpleDOM(
			'<root>
				<child2 a="aval" b="bval" />
			</root>'
		);

		$root->child1->copyAttributesFrom($other->child2);

		$this->assertXmlStringEqualsXmlString(
			'<root>
				<child1 a="aval" b="bval" />
			</root>',
			$root->asXML()
		);
	}

	public function testNSAttributesAreCopied()
	{
		$root = new SimpleDOM(
			'<root>
				<child1 />
				<child2 xmlns:foo="urn:foo" foo:a="foo:aval" a="aval" b="bval" />
			</root>'
		);

		$root->child1->copyAttributesFrom($root->child2);

		$this->assertXmlStringEqualsXmlString(
			'<root>
				<child1 xmlns:foo="urn:foo" foo:a="foo:aval" a="aval" b="bval" />
				<child2 xmlns:foo="urn:foo" foo:a="foo:aval" a="aval" b="bval" />
			</root>',
			$root->asXML()
		);
	}

	public function testExistingAttributesAreOverwrittenByDefault()
	{
		$root = new SimpleDOM(
			'<root>
				<child1 a="old" />
				<child2 a="aval" b="bval" />
			</root>'
		);

		$root->child1->copyAttributesFrom($root->child2);

		$this->assertXmlStringEqualsXmlString(
			'<root>
				<child1 a="aval" b="bval" />
				<child2 a="aval" b="bval" />
			</root>',
			$root->asXML()
		);
	}

	public function testExistingAttributesCanBePreserved()
	{
		$root = new SimpleDOM(
			'<root>
				<child1 a="old" />
				<child2 a="aval" b="bval" />
			</root>'
		);

		$root->child1->copyAttributesFrom($root->child2, false);

		$this->assertXmlStringEqualsXmlString(
			'<root>
				<child1 a="old" b="bval" />
				<child2 a="aval" b="bval" />
			</root>',
			$root->asXML()
		);
	}

	public function testNSDeclarationsAreNotAttributesAndAreNotCopiedUnlessNeeded()
	{
		$root = new SimpleDOM(
			'<root>
				<child1 />
				<child2 a="aval" b="bval" xmlns:foo="urn:foo" />
			</root>'
		);

		$root->child1->copyAttributesFrom($root->child2);

		$this->assertXmlStringEqualsXmlString(
			'<root>
				<child1 a="aval" b="bval" />
				<child2 a="aval" b="bval" xmlns:foo="urn:foo" />
			</root>',
			$root->asXML()
		);
	}
}