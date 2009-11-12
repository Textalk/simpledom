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
 
class SimpleDOM_TestCase_insertPI extends PHPUnit_Framework_TestCase
{
	public function testDefaultModeIsBefore()
	{
		$root = new SimpleDOM('<root />');
		$expected_xml = '<?test ?><root />';

		$return = $root->insertPI('test');

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testAppend()
	{
		$root = new SimpleDOM('<root />');
		$expected_xml = '<root><?test ?></root>';

		$return = $root->insertPI('test', null, 'append');

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testAfter()
	{
		$root = new SimpleDOM('<root />');
		$expected_xml = '<root /><?test ?>';

		$return = $root->insertPI('test', null, 'after');

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testNoData()
	{
		$root = new SimpleDOM('<root />');
		$expected_xml = '<?xml-stylesheet?><root />';

		$return = $root->insertPI('xml-stylesheet', null, 'before');

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testString()
	{
		$root = new SimpleDOM('<root />');
		$expected_xml = '<?xml-stylesheet type="text/xsl" href="foo.xsl"?><root />';

		$return = $root->insertPI('xml-stylesheet', 'type="text/xsl" href="foo.xsl"', 'before');

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testArray()
	{
		$root = new SimpleDOM('<root />');
		$expected_xml = '<?xml-stylesheet type="text/xsl" href="foo.xsl"?><root />';

		$return = $root->insertPI('xml-stylesheet', array(
			'type' => 'text/xsl',
			'href' => 'foo.xsl'
		), 'before');

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	public function testMultiple()
	{
		$root = new SimpleDOM('<root />');
		$expected_xml = '<?xml-stylesheet type="text/xsl" href="foo.xsl"?><?xml-stylesheet type="text/xsl" href="bar.xsl"?><root />';

		$root->insertPI('xml-stylesheet', 'type="text/xsl" href="foo.xsl"', 'before');
		$root->insertPI('xml-stylesheet', 'type="text/xsl" href="bar.xsl"', 'before');

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_xml);
	}

	/**
	* @expectedException DOMException
	*/
	public function testInvalidTarget()
	{
		$root = new SimpleDOM('<root />');

		try
		{
			$root->insertPI('$$$', 'type="text/xsl" href="foo.xsl"');
		}
		catch (DOMException $e)
		{
			$this->assertSame($e->code, DOM_INVALID_CHARACTER_ERR);
			throw $e;
		}
	}
}