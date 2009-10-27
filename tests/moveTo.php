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
 
class SimpleDOM_TestCase_moveTo extends PHPUnit_Framework_TestCase
{
	public function testResult()
	{
		$root		= new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$expected	= '<root><child2><child1 /></child2><child3 /></root>';

		$root->child1->moveTo($root->child2);

		$this->assertXmlStringEqualsXmlString($expected, $root->asXML());
	}

	public function testReturn()
	{
		$root		= new SimpleDOM('<root><child1 /><child2 /><child3 /></root>');
		$expected	= '<root><child2><child1 moved="1" /></child2><child3 /></root>';

		$return = $root->child1->moveTo($root->child2);
		$return['moved'] = 1;

		$this->assertXmlStringEqualsXmlString($expected, $root->asXML());
	}

	public function testMultipleDocuments()
	{
		$doc1 = new SimpleDOM('<doc1><child1 /><child2 /></doc1>');
		$doc2 = new SimpleDOM('<doc2><child3 /></doc2>');

		$doc2->child3->moveTo($doc1);

		$this->assertXmlStringEqualsXmlString(
			'<doc1><child1 /><child2 /><child3 /></doc1>',
			$doc1->asXML()
		);

		$this->assertXmlStringEqualsXmlString(
			'<doc2 />',
			$doc2->asXML()
		);
	}

	public function testNS()
	{
		$root		= new SimpleDOM(
			'<root>
				<ns:child1 xmlns:ns="urn:ns">
					<ns:grandchild1 />
				</ns:child1>
				<child2 />
				<child3 />
			</root>',

			LIBXML_NOBLANKS
		);
		$expected	= 
			'<root>
				<ns:child1 xmlns:ns="urn:ns" />
				<child2>
					<ns:grandchild1 xmlns:ns="urn:ns" moved="1" />
				</child2>
				<child3 />
			</root>';

		$nodes	= $root->children('urn:ns');
		$node	= $nodes[0]->grandchild1;

		$return = $node->moveTo($root->child2);
		$return['moved'] = 1;

		$this->assertXmlStringEqualsXmlString($expected, $root->asXML());
	}
}