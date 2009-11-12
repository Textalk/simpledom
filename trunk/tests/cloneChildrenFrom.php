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
 
class SimpleDOM_TestCase_cloneChildrenFrom extends PHPUnit_Framework_TestCase
{
	public function testMultipleDocuments()
	{
		$doc1 = new SimpleDOM('<doc1 />');
		$doc2 = new SimpleDOM('<doc2><child1 /><child2 /><child3 /></doc2>');

		$doc1->cloneChildrenFrom($doc2);

		$this->assertXmlStringEqualsXmlString(
			'<doc1><child1 /><child2 /><child3 /></doc1>',
			$doc1->asXML()
		);
	}

	public function testMultipleDocumentsNS()
	{
		$doc1 = new SimpleDOM('<doc1 />');
		$doc2 = new SimpleDOM('<doc2 xmlns:ns="urn:ns"><ns:child1 /><child2 /><child3 /></doc2>');

		$doc1->cloneChildrenFrom($doc2);

		$this->assertXmlStringEqualsXmlString(
			'<doc1><ns:child1  xmlns:ns="urn:ns"/><child2 /><child3 /></doc1>',
			$doc1->asXML()
		);
	}

	public function testNodesAreNotBoundToSourceDocument()
	{
		$doc1 = new SimpleDOM('<doc1 />');
		$doc2 = new SimpleDOM('<doc2><child1 /><child2 /><child3 /></doc2>');

		$doc1->cloneChildrenFrom($doc2);

		$doc1->child1['doc'] = 1;
		$doc2->child1['doc'] = 2;

		$this->assertXmlStringEqualsXmlString(
			'<doc1><child1 doc="1" /><child2 /><child3 /></doc1>',
			$doc1->asXML()
		);

		$this->assertXmlStringEqualsXmlString(
			'<doc2><child1 doc="2" /><child2 /><child3 /></doc2>',
			$doc2->asXML()
		);
	}

	public function testCloningIsDeepByDefault()
	{
		$doc1 = new SimpleDOM('<doc1 />');
		$doc2 = new SimpleDOM(
			'<doc2>
				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>
			</doc2>'
		);

		$doc1->cloneChildrenFrom($doc2);

		$this->assertXmlStringEqualsXmlString(
			'<doc1>
				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>
			</doc1>',
			$doc1->asXML()
		);
	}

	public function testCloningCanBeShallow()
	{
		$doc1 = new SimpleDOM('<doc1 />');
		$doc2 = new SimpleDOM(
			'<doc2>
				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>
			</doc2>'
		);

		$doc1->cloneChildrenFrom($doc2, false);

		$this->assertXmlStringEqualsXmlString(
			'<doc1>
				<child1 />
				<child2 />
				<child3 />
			</doc1>',
			$doc1->asXML()
		);
	}

	public function testCloningFromSameNode()
	{
		$node = new SimpleDOM(
			'<node>
				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>
			</node>'
		);

		$node->cloneChildrenFrom($node, true);

		$this->assertXmlStringEqualsXmlString(
			'<node>
				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>

				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>
			</node>',
			$node->asXML()
		);
	}

	public function testCloningFromDescendantNode()
	{
		$node = new SimpleDOM(
			'<node>
				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>
			</node>'
		);

		$node->cloneChildrenFrom($node->child1, true);

		$this->assertXmlStringEqualsXmlString(
			'<node>
				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>

				<granchild1 />
			</node>',
			$node->asXML()
		);
	}

	public function testCloningFromAscendantNode()
	{
		$node = new SimpleDOM(
			'<node>
				<child1><granchild1 /></child1>
				<child2 />
				<child3><granchild3 /></child3>
			</node>'
		);

		$node->child1->cloneChildrenFrom($node, true);

		$this->assertXmlStringEqualsXmlString(
			'<node>
				<child1>
					<granchild1 />

					<child1><granchild1 /></child1>
					<child2 />
					<child3><granchild3 /></child3>
				</child1>
				<child2 />
				<child3><granchild3 /></child3>
			</node>',
			$node->asXML()
		);
	}
}