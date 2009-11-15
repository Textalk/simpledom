#!/bin/bash
phpdoc -pp off -f $(cd $(dirname $0); pwd -P)/SimpleDOM.php  -t $(cd $(dirname $0); pwd -P)/doc/
