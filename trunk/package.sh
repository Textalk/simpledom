#!/bin/bash
cd $(cd $(dirname $0); pwd -P)
./generate_doc.sh
7z a -mx=9 -tzip SimpleDOM.zip SimpleDOM.php doc