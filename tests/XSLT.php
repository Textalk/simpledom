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
 
class SimpleDOM_TestCase_XSLT extends PHPUnit_Framework_TestCase
{
	public function testXSLTProcessor()
	{
		$xml = new SimpleDOM('<xml><child>CHILD</child></xml>');

		$this->assertXmlStringEqualsXmlString(
			'<output><child>Content: CHILD</child></output>',
			$xml->XSLT($this->filepath, false)
		);
	}

	public function testXSLCache()
	{
		if (!extension_loaded('xslcache'))
		{
			$this->markTestSkipped('The XSL Cache extension is not available');
			return;
		}

		$xml = new SimpleDOM('<xml><child>CHILD</child></xml>');

		$this->assertXmlStringEqualsXmlString(
			'<output><child>Content: CHILD</child></output>',
			$xml->XSLT($this->filepath, true)
		);
	}

	/**
	* Internal stuff
	*/
	public function setUp()
	{
		$this->filepath = sys_get_temp_dir() . '/SimpleDOM_TestCase_XSLT.xsl';

		file_put_contents(
			$this->filepath,
			'<?xml version="1.0" encoding="utf-8"?>
			<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

				<xsl:output method="xml" />

				<xsl:template match="/">
					<output>
						<xsl:for-each select="//child">
							<child>Content: <xsl:value-of select="." /></child>
						</xsl:for-each>
					</output>
				</xsl:template>

			</xsl:stylesheet>'
		);
	}

	public function tearDown()
	{
		unlink($this->filepath);
	}
}