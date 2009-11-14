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
 
class SimpleDOM_TestCase_sort extends PHPUnit_Framework_TestCase
{
	public function testByAttribute()
	{
		$actual = array(
			new SimpleDOM('<child letter="c" />'),
			new SimpleDOM('<child letter="a" />'),
			new SimpleDOM('<child letter="e" />'),
			new SimpleDOM('<child letter="b" />'),
			new SimpleDOM('<child letter="d" />')
		);

		SimpleDOM::sort($actual, '@letter');

		$expected = array(
			new SimpleDOM('<child letter="a" />'),
			new SimpleDOM('<child letter="b" />'),
			new SimpleDOM('<child letter="c" />'),
			new SimpleDOM('<child letter="d" />'),
			new SimpleDOM('<child letter="e" />')
		);

		$this->assertEquals($expected, $actual);
	}

	public function testPointersToNodesAreNotLost()
	{
		$actual = array(
			new SimpleDOM('<child letter="c" />'),
			new SimpleDOM('<child letter="d" />'),
			new SimpleDOM('<child letter="e" />'),
			new SimpleDOM('<child letter="a" />'),
			new SimpleDOM('<child letter="b" />')
		);

		$c = $actual[0];
		$d = $actual[1];
		$e = $actual[2];
		$a = $actual[3];
		$b = $actual[4];

		SimpleDOM::sort($actual, '@letter');

		$a['old_letter'] = 'a';
		$b['old_letter'] = 'b';
		$c['old_letter'] = 'c';
		$d['old_letter'] = 'd';
		$e['old_letter'] = 'e';

		$expected = array(
			new SimpleDOM('<child letter="a" old_letter="a" />'),
			new SimpleDOM('<child letter="b" old_letter="b" />'),
			new SimpleDOM('<child letter="c" old_letter="c" />'),
			new SimpleDOM('<child letter="d" old_letter="d" />'),
			new SimpleDOM('<child letter="e" old_letter="e" />')
		);

		$this->assertEquals($expected, $actual);
	}
}