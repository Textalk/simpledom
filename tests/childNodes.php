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
 
class SimpleDOM_TestCase_childNodes extends PHPUnit_Framework_TestCase
{
	public function test()
	{
		$root = new SimpleDOM(
			'<root>
				<child1 />
				<child2 />
				<child3>
					<grandchild />
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_return = array(
			new SimpleDOM('<child1 />'),
			new SimpleDOM('<child2 />'),
			new SimpleDOM('<child3><grandchild /></child3>'),
		);

		$return = $root->childNodes();

		$this->assertEquals($expected_return, $return);
	}

	public function testTextNodes()
	{
		$root = new SimpleDOM(
			'<root>Some <b>bold</b> text</root>'
		);

		$expected_return = array(
			'Some ',
			new SimpleDOM('<b>bold</b>'),
			' text'
		);

		$return = $root->childNodes();

		$this->assertEquals($expected_return, $return);
	}
}