#!/bin/bash
PASSED=$1;

if [[ -d "$PASSED" ]]; then
    echo dir
elif [[ -f "$PASSED" ]]; then
    echo file
else
    echo inv
    exit 1
fi