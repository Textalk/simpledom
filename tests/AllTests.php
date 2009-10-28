<?php
/*

Copyright 2007 The SimpleDOM Working Group Initiative

Permission is hereby granted, free of charge, to any person	obtaining a	copy
of this	software and associated	documentation files	(the "Software"), to deal
in the Software	without	restriction, including without limitation the rights
to use,	copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software,	and	to permit persons to whom the Software is
furnished to do	so,	subject	to the following conditions:

The	above copyright	notice and this	permission notice shall	be included	in
all	copies or substantial portions of the Software.

THE	SOFTWARE IS	PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,	EXPRESS	OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF	MERCHANTABILITY,
FITNESS	FOR	A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO	EVENT SHALL	THE
AUTHORS	OR COPYRIGHT HOLDERS BE	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION	OF CONTRACT, TORT OR OTHERWISE,	ARISING	FROM,
OUT	OF OR IN CONNECTION	WITH THE SOFTWARE OR THE USE OR	OTHER DEALINGS IN
THE	SOFTWARE.

*/

if (!defined('PHPUnit_MAIN_METHOD'))
{
	define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
 
require_once dirname(__FILE__) . '/../SimpleDOM.php';
 
class AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}
 
	public static function suite()
	{
		PHPUnit_Util_Filter::addDirectoryToFilter(dirname(__FILE__));

		$suite = new PHPUnit_Framework_TestSuite();
		$suite->setName('SimpleDOM');

		foreach	(glob(dirname(__FILE__)	. '/*.php')	as $filepath)
		{
			$name =	basename($filepath,	'.php');

			if ($name != 'AllTests')
			{
				$suite->addTestFile($filepath);
			}
		}
 
		return $suite;
	}
}
 
if (PHPUnit_MAIN_METHOD	== 'AllTests::main')
{
	AllTests::main();
}