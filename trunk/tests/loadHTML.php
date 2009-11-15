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
 
class SimpleDOM_TestCase_loadHTML extends PHPUnit_Framework_TestCase
{
	public function testWellFormedXML()
	{
		$html =
			'<html>
				<head>
					<title>Hello HTML</title>
				</head>
				<body>
					<p>Hello World!</p>
				</body>
			</html>';

		$node = SimpleDOM::loadHTML($html);

		$this->assertXmlStringEqualsXmlString($html, $node->asXML());
	}

	public function testFromValidHTMLMalformedXML()
	{
		$html =
			'<html>
				<head>
					<title>Hello HTML</title>
					<link rel="stylesheet" type="text/css" href="test.css">
				</head>
				<body>
					<p>Hello World!<br><font size=1>New line</font></p>
				</body>
			</html>';

		$xml =
			'<html>
				<head>
					<title>Hello HTML</title>
					<link rel="stylesheet" type="text/css" href="test.css" />
				</head>
				<body>
					<p>Hello World!<br /><font size="1">New line</font></p>
				</body>
			</html>';

		$node = SimpleDOM::loadHTML($html);

		$this->assertXmlStringEqualsXmlString($xml, $node->asXML());
	}

	public function testInvalidHTMLEntityAreSilentlyFixed()
	{
		$html =
			'<html>
				<body>
					<p><a href="test?a=1&b=2">link</a></p>
				</body>
			</html>';

		$xml =
			'<html>
				<body>
					<p><a href="test?a=1&amp;b=2">link</a></p>
				</body>
			</html>';

		$node = SimpleDOM::loadHTML($html);

		$this->assertXmlStringEqualsXmlString($xml, $node->asXML());
	}

	public function testErrorsCanBeRetrieved()
	{
		$html =
			'<html>
				<body>
					<p><a href="test?a=1&b=2">link</a>
					<p><i>not closed
				</body>
			</html>';

		$node = SimpleDOM::loadHTML($html, $errors);

		$this->assertType('array', $errors, '$errors was not initialized as an array');

		if (is_array($errors))
		{
			$this->assertSame(2, count($errors), '$errors did not contain the expected number of errors');

			$errors = array_values(array_slice($errors, -2));

			$this->assertStringStartsWith("htmlParseEntityRef: expecting ';'", $errors[0]->message);
			$this->assertStringStartsWith("Opening and ending tag mismatch: body and i", $errors[1]->message);
		}
	}

	/**
	* @depends testErrorsCanBeRetrieved
	*/
	public function testOnlyRelevantErrorsAreReturned()
	{
		/**
		* Generate some errors then rerun testErrorsCanBeRetrieved
		*/
		$old = libxml_use_internal_errors(true);
		SimpleDOM::loadHTML('<html><bogus>');
		$this->testErrorsCanBeRetrieved();
		libxml_use_internal_errors($old);
	}
}