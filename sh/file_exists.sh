#!/bin/bash
PASSED=$1;

if [ -e "$PASSED" ]; then
	echo exists
else
	echo nope
	exit 1;
fi;
