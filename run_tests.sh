#!/bin/bash
phpunit --coverage-html $(cd $(dirname $0); pwd -P)/tests/coverage $(cd $(dirname $0); pwd -P)/tests/AllTests.php