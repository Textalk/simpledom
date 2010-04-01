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
 
class SimpleDOM_TestCase_innerXML extends PHPUnit_Framework_TestCase
{
	protected function assertContentIsSame($content)
	{
		$node = new SimpleDOM('<node>' . $content . '</node>', LIBXML_NOENT);
		$this->assertSame($content, $node->innerXML());
	}

	public function testElementsArePreserved()
	{
		$this->assertContentIsSame('This is a <b>bold</b>');
	}

	public function testHTMLEntitiesArePreserved()
	{
		$this->assertContentIsSame('This is an &amp;ampersand');
	}

	public function testCDATASectionsArePreserved()
	{
		$this->assertContentIsSame('This is a <![CDATA[<CDATA>]]> section');
	}
}