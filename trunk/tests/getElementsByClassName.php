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
 
class SimpleDOM_TestCase_getElementsByClassName extends PHPUnit_Framework_TestCase
{
	public function testMatch()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="bar" />
				<div class="foo" />
				<div />
			</node>'
		);

		$actual = $node->getElementsByClassName('foo');

		$expected = array(
			$node->div[1],
		);

		$this->assertEquals($expected, $actual);
	}

	public function testMultipleMatches()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="bar" />
				<div id="first" class="foo" />
				<div>
					<div id="second" class="foo" />
				</div>
			</node>'
		);

		$actual = $node->getElementsByClassName('foo');

		$expected = array(
			$node->div[1],
			$node->div[2]->div,
		);

		$this->assertEquals($expected, $actual);
	}

	public function testNoMatch()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="bar" />
				<div class="foo" />
				<div />
			</node>'
		);

		$actual = $node->getElementsByClassName('baz');

		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	public function testNoSubstringMatch()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="bar" />
				<div class="foobar" />
				<div />
			</node>'
		);

		$actual = $node->getElementsByClassName('foo');

		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	public function testMatchLeading()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="quux" />
				<div class="foo bar baz" />
				<div />
			</node>'
		);

		$actual = $node->getElementsByClassName('foo');

		$expected = array(
			$node->div[1],
		);

		$this->assertEquals($expected, $actual);
	}

	public function testMatchMiddle()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="quux" />
				<div class="foo bar baz" />
				<div />
			</node>'
		);

		$actual = $node->getElementsByClassName('bar');

		$expected = array(
			$node->div[1],
		);

		$this->assertEquals($expected, $actual);
	}

	public function testMatchTrailing()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="quux" />
				<div class="foo bar baz" />
				<div />
			</node>'
		);

		$actual = $node->getElementsByClassName('baz');

		$expected = array(
			$node->div[1],
		);

		$this->assertEquals($expected, $actual);
	}

	public function testSingleQuotesReturnNothing()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="quux" />
				<div class="foo bar baz" />
				<div />
			</node>'
		);

		$actual = $node->getElementsByClassName("'foo");

		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	public function testDoubleQuotesReturnNothing()
	{
		$node = new SimpleDOM(
			'<node>
				<div class="quux" />
				<div class="foo bar baz" />
				<div />
			</node>'
		);

		$actual = $node->getElementsByClassName('"foo');

		$expected = array();

		$this->assertEquals($expected, $actual);
	}

	public function testChildContext()
	{
		$node = new SimpleDOM(
			'<node>
				<div id="first" class="foo" />
				<div>
					<div id="second" class="foo" />
				</div>
			</node>'
		);

		$actual = $node->div[1]->getElementsByClassName('foo');

		$expected = array(
			$node->div[1]->div
		);

		$this->assertEquals($expected, $actual);
	}

	public function testContextNodeIsNotReturned()
	{
		$node = new SimpleDOM(
			'<node>
				<div id="first" class="foo">
					<div id="second" class="foo" />
				</div>
			</node>'
		);

		$actual = $node->div->getElementsByClassName('foo');

		$expected = array(
			$node->div->div
		);

		$this->assertEquals($expected, $actual);
	}
}