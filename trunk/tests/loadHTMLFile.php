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
 
class SimpleDOM_TestCase_loadHTMLFile extends PHPUnit_Framework_TestCase
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

		$node = SimpleDOM::loadHTMLFile($this->file($html));

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

		$node = SimpleDOM::loadHTMLFile($this->file($html));

		$this->assertXmlStringEqualsXmlString($xml, $node->asXML());
	}

	public function testInvalidHTMLEntityAreMagicallyFixed()
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

		$node = @SimpleDOM::loadHTMLFile($this->file($html));

		$this->assertXmlStringEqualsXmlString($xml, $node->asXML());
	}

	/**
	* Internal stuff
	*/
	protected function file($contents)
	{
		$this->filepath = sys_get_temp_dir() . '/SimpleDOM_TestCase_loadHTMLFile.html';

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