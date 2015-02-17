# Behat Guzzle Extension

[![Build Status](https://travis-ci.org/teaandcode/behat-guzzle-extension.svg)](https://travis-ci.org/teaandcode/behat-guzzle-extension)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/?branch=master)


Guzzle extension for Behat is an integration layer between Behat 2.4+ and Guzzle
3.5+ and it provides:

* Additional services for Behat (``Guzzle``).
* ``Behat\GuzzleExtension\Context\GuzzleAwareContext`` which provides ``Guzzle``
  instance for your contexts.
* Base ``Behat\GuzzleExtension\Context\GuzzleContext`` context which provides
  base step definitions and hooks for your contexts or subcontexts. Or it could
  be even used as context on its own.

## To do

* Add documentation
* Add language support

## Copyright

Copyright (c) 2015 Dave Nash (knasher). See LICENSE for details.

## Contributors

* Dave Nash [knasher](http://github.com/knasher) [lead developer]
* Other [awesome developers]
  (https://github.com/Behat/GuzzleExtension/graphs/contributors)