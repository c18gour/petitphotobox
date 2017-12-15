#!/usr/bin/env python

"""
A script to verify coding standards.

Open the terminal and execute the following command:

   ./phpcs.py

"""

import subprocess

subprocess.call(
  ["phpcs", "--standard=phpcs.xml", "--ignore=app/src/vendor", "app/src"]
)
