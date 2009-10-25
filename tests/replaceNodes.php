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
 
class SimpleDOM_TestCase_replaceNodes extends PHPUnit_Framework_TestCase
{
	public function testRootContext()
	{
		$xpath = '//*[@replace="1"]';

		$root = new SimpleDOM(
			'<root>
				<child1 replace="1" />
				<child2 replace="0" />
				<child3>
					<grandchild replace="1" />
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$new = new SimpleDOM('<new />');

		$expected_result = new SimpleDOM(
			'<root>
				<new />
				<child2 replace="0" />
				<child3>
					<new />
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_return = array(
			clone $root->child1,
			clone $root->child3->grandchild
		);

		$return = $root->replaceNodes($xpath, $new);

		$this->assertEquals($expected_result, $root);
		$this->assertEquals($expected_return, $return);
	}

	public function testChildContext()
	{
		$xpath = './/*[@replace="1"]';

		$root = new SimpleDOM(
			'<root>
				<child1 replace="1" />
				<child2 replace="0" />
				<child3>
					<grandchild>
						<grandgrandchild replace="1" />
					</grandchild>
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$new = new SimpleDOM('<new />');

		$expected_result = new SimpleDOM(
			'<root>
				<child1 replace="1" />
				<child2 replace="0" />
				<child3>
					<grandchild>
						<new />
					</grandchild>
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_return = array(
			clone $root->child3->grandchild->grandgrandchild
		);

		$return = $root->child3->replaceNodes($xpath, $new);

		$this->assertEquals($expected_result, $root);
		$this->assertEquals($expected_return, $return);
	}

	public function testChildContextNoMatches()
	{
		$xpath = './*[@replace="1"]';

		$root = new SimpleDOM(
			'<root>
				<child1 replace="1" />
				<child2 replace="0" />
				<child3>
					<grandchild>
						<grandgrandchild replace="1" />
					</grandchild>
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$new = new SimpleDOM('<new />');

		$expected_result = clone $root;
		$expected_return = array();

		$return = $root->child3->replaceNodes($xpath, $new);

		$this->assertEquals($expected_result, $root);
		$this->assertEquals($expected_return, $return);
	}

	public function testRoot()
	{
		$root	= new SimpleDOM('<root />');
		$new	= new SimpleDOM('<new />');

		$expected_result = clone $new;
		$expected_return = array(
			clone $root
		);

		$return = $root->replaceNodes('/root', $new);
		
		$this->assertEquals($expected_result, $root);
		$this->assertEquals($expected_return, $return);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testInvalidArgumentType()
	{
		$root = new SimpleDOM('<root />');
		$new = new SimpleDOM('<new />');

		$root->replaceNodes(false, $new);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testInvalidXPath()
	{
		$root = new SimpleDOM('<root />');
		$new = new SimpleDOM('<new />');

		$root->replaceNodes('????', $new);
	}
}