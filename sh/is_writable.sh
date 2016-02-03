#!/bin/bash
PASSED=$1;

if test -w "$PASSED"; then
    echo writable
else
    echo false
    exit 1
fi
