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
 
class SimpleDOM_TestCase_simpledom_load_file extends PHPUnit_Framework_TestCase
{
	public function test()
	{
		$xml = '<root><child1 /><child2 /><child3 /></root>';
		$node = simpledom_load_file($this->file($xml));

		$this->assertSame('SimpleDOM', get_class($node), 'Wrong class returned');
		$this->assertXmlStringEqualsXmlString($xml, $node->asXML());
	}

	/**
	* Internal stuff
	*/
	protected function file($contents)
	{
		$this->filepath = sys_get_temp_dir() . '/SimpleDOM_TestCase_simpledom_load_file.xml';

		file_put_contents(
			$this->filepath,
			$contents
		);

		return $this->filepath;
	}

	public function tearDown()
	{
		unlink($this->filepath);
	}
}