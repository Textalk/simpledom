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
 
class SimpleDOM_TestCase_sortedXPath extends PHPUnit_Framework_TestCase
{
	public function testByAttribute()
	{
		$node = new SimpleDOM(
			'<node>
				<child letter="a" />
				<child letter="d" />
				<child letter="b" />
				<child letter="c" />
				<child letter="e" />
			</node>'
		);

		$expected = array(
			new SimpleDOM('<child letter="a" />'),
			new SimpleDOM('<child letter="b" />'),
			new SimpleDOM('<child letter="c" />'),
			new SimpleDOM('<child letter="d" />'),
			new SimpleDOM('<child letter="e" />')
		);

		$actual = $node->sortedXPath('//child', '@letter');

		$this->assertEquals($expected, $actual);
	}

	public function testByNumericAttribute()
	{
		$node = new SimpleDOM(
			'<node>
				<child number="3" />
				<child number="11" />
				<child number="1" />
				<child number="2" />
			</node>'
		);

		$expected = array(
			new SimpleDOM('<child number="1" />'),
			new SimpleDOM('<child number="2" />'),
			new SimpleDOM('<child number="3" />'),
			new SimpleDOM('<child number="11" />')
		);

		$actual = $node->sortedXPath('//child', '@number', SORT_NUMERIC);
		$this->assertEquals($expected, $actual);
	}

	public function testByStringAttribute()
	{
		$node = new SimpleDOM(
			'<node>
				<child number="3" />
				<child number="11" />
				<child number="1" />
				<child number="2" />
			</node>'
		);

		$expected = array(
			new SimpleDOM('<child number="1" />'),
			new SimpleDOM('<child number="11" />'),
			new SimpleDOM('<child number="2" />'),
			new SimpleDOM('<child number="3" />')
		);

		$actual = $node->sortedXPath('//child', '@number', SORT_STRING);
		$this->assertEquals($expected, $actual);
	}

	public function testByMultipleAttributes()
	{
		$node = new SimpleDOM(
			'<node>
				<child letter="e" number="2" />
				<child letter="d" number="3" />
				<child letter="b" number="1" />
				<child letter="c" number="1" />
				<child letter="a" number="2" />
			</node>'
		);

		$expected = array(
			new SimpleDOM('<child letter="b" number="1" />'),
			new SimpleDOM('<child letter="c" number="1" />'),
			new SimpleDOM('<child letter="a" number="2" />'),
			new SimpleDOM('<child letter="e" number="2" />'),
			new SimpleDOM('<child letter="d" number="3" />')
		);

		$actual = $node->sortedXPath('//child', '@number', '@letter');
		$this->assertEquals($expected, $actual);
	}

	public function testByMultipleAttributesDifferentOrders()
	{
		$node = new SimpleDOM(
			'<node>
				<child letter="e" number="2" />
				<child letter="d" number="3" />
				<child letter="b" number="1" />
				<child letter="c" number="1" />
				<child letter="a" number="2" />
			</node>'
		);

		$expected = array(
			new SimpleDOM('<child letter="d" number="3" />'),
			new SimpleDOM('<child letter="a" number="2" />'),
			new SimpleDOM('<child letter="e" number="2" />'),
			new SimpleDOM('<child letter="b" number="1" />'),
			new SimpleDOM('<child letter="c" number="1" />')
		);

		$actual = $node->sortedXPath('//child', '@number', SORT_DESC, '@letter');
		$this->assertEquals($expected, $actual);
	}

	public function testByChild()
	{
		$node = new SimpleDOM(
			'<node>
				<child><letter>e</letter></child>
				<child><letter>b</letter></child>
				<child><letter>c</letter></child>
				<child><letter>d</letter></child>
				<child><letter>a</letter></child>
			</node>'
		);

		$expected = array(
			new SimpleDOM('<child><letter>a</letter></child>'),
			new SimpleDOM('<child><letter>b</letter></child>'),
			new SimpleDOM('<child><letter>c</letter></child>'),
			new SimpleDOM('<child><letter>d</letter></child>'),
			new SimpleDOM('<child><letter>e</letter></child>')
		);

		$actual = $node->sortedXPath('//child', 'letter');

		$this->assertEquals($expected, $actual);
	}

	public function testByMissingChild()
	{
		$node = new SimpleDOM(
			'<node>
				<child><letter>e</letter></child>
				<child><letter>b</letter></child>
				<child />
				<child><letter>c</letter></child>
				<child><letter>d</letter></child>
				<child><letter>a</letter></child>
			</node>'
		);

		$expected = array(
			new SimpleDOM('<child />'),
			new SimpleDOM('<child><letter>a</letter></child>'),
			new SimpleDOM('<child><letter>b</letter></child>'),
			new SimpleDOM('<child><letter>c</letter></child>'),
			new SimpleDOM('<child><letter>d</letter></child>'),
			new SimpleDOM('<child><letter>e</letter></child>')
		);

		$actual = $node->sortedXPath('//child', 'letter');

		$this->assertEquals($expected, $actual);
	}

	public function testBySelf()
	{
		$node = new SimpleDOM(
			'<node>
				<child><letter>e</letter></child>
				<child><letter>b</letter></child>
				<child><letter>c</letter></child>
				<child><letter>d</letter></child>
				<child><letter>a</letter></child>
			</node>'
		);

		$expected = array(
			new SimpleDOM('<child><letter>a</letter></child>'),
			new SimpleDOM('<child><letter>b</letter></child>'),
			new SimpleDOM('<child><letter>c</letter></child>'),
			new SimpleDOM('<child><letter>d</letter></child>'),
			new SimpleDOM('<child><letter>e</letter></child>')
		);

		$actual = $node->sortedXPath('//child', '.');

		$this->assertEquals($expected, $actual);
	}
}