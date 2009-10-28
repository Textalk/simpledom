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
 
class SimpleDOM_TestCase_asPrettyXML extends PHPUnit_Framework_TestCase
{
	public function testGrandChildrenAreIndented()
	{
		$root = new SimpleDOM('<root><child1><grandchild1 /></child1><child2 /><child3 /></root>');

		$expected = '<?xml version="1.0"?>
<root>
  <child1>
    <grandchild1/>
  </child1>
  <child2/>
  <child3/>
</root>';

		$this->assertSame($expected, $root->asPrettyXML());
	}

	public function testCommentsArePreserved()
	{
		$root = new SimpleDOM('<root><!--COMMENT--><child1 /><child2 /><child3 /></root>');

		$expected = '<?xml version="1.0"?>
<root>
  <!--COMMENT-->
  <child1/>
  <child2/>
  <child3/>
</root>';

		$this->assertSame($expected, $root->asPrettyXML());
	}

	public function testPIsArePreserved()
	{
		$root = new SimpleDOM('<?xml-stylesheet type="text/xsl" href="foobar.xsl" ?><root><child1 /><child2 /><child3 /></root>');

		$expected = '<?xml version="1.0"?>
<?xml-stylesheet type="text/xsl" href="foobar.xsl" ?><root>
  <child1/>
  <child2/>
  <child3/>
</root>';

		$this->assertSame($expected, $root->asPrettyXML());
	}

	public function testCDATASectionsAreResolved()
	{
		$root = new SimpleDOM('<root><child1 /><cdata><![CDATA[<foobar>]]></cdata><child2 /><child3 /></root>');

		$expected = '<?xml version="1.0"?>
<root>
  <child1/>
  <cdata>&lt;foobar&gt;</cdata>
  <child2/>
  <child3/>
</root>';

		$this->assertSame($expected, $root->asPrettyXML());
	}

	public function testDoesNotInterfereWithTextNodes()
	{
		$root = new SimpleDOM('<root><child1>text<grandchild1 /></child1><child2 /><child3 /></root>');

		$expected = '<?xml version="1.0"?>
<root>
  <child1>text<grandchild1/></child1>
  <child2/>
  <child3/>
</root>';

		$this->assertSame($expected, $root->asPrettyXML());
	}
}