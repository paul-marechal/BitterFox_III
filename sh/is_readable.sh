#!/bin/bash
PASSED=$1;

if test -r "$PASSED"; then
    echo readable
else
    echo false
    exit 1
fi
