#!/usr/bin/env python

""" A script to verify coding standards. """

import subprocess

subprocess.call(
  ["phpcs", "--standard=phpcs.xml", "--ignore=app/src/vendor", "app/src"]
)
