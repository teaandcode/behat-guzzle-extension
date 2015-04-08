# Behat Guzzle Extension

[![Build Status](https://travis-ci.org/teaandcode/behat-guzzle-extension.svg?branch=master)](https://travis-ci.org/teaandcode/behat-guzzle-extension)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/build-status/master)

Guzzle extension for Behat is an integration layer between Behat 3.0+ and Guzzle
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

## Configuration

### Example Configuration

```
default:
    extensions:
        Behat\GuzzleExtension:
            base_url: http://127.0.0.1
            service_descriptions: %paths.base%/app/config/service.json
    suites:
        default:
            contexts:
                - Behat\GuzzleExtension\Context\GuzzleContext:
                    users:
                        test.user.1: B8E...1AF
                        test.user.2: A6B...8E6
```

## Copyright

Copyright (c) 2015 Dave Nash (knasher). See LICENSE for details.

## Contributors

* Dave Nash [knasher](http://github.com/knasher) [lead developer]
* Other [awesome developers]
  (https://github.com/teaandcode/behat-guzzle-extension/graphs/contributors)