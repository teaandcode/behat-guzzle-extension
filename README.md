# Behat Guzzle Extension

[![Build Status](https://travis-ci.org/teaandcode/behat-guzzle-extension.svg?branch=master)](https://travis-ci.org/teaandcode/behat-guzzle-extension)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/teaandcode/behat-guzzle-extension/build-status/master)

[![Join the chat at https://gitter.im/teaandcode/behat-guzzle-extension](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/teaandcode/behat-guzzle-extension?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Guzzle extension for Behat is an integration layer between Behat 3.0+ and Guzzle
3.5+ and it provides:

* Additional services for Behat (``Guzzle``).
* ``Behat\GuzzleExtension\Context\GuzzleAwareContext`` which provides ``Guzzle``
  instance for your contexts.
* Base ``Behat\GuzzleExtension\Context\GuzzleContext`` context which provides
  base step definitions and hooks for your contexts or subcontexts. Or it could
  be even used as context on its own.

## Installation

Add to your project with Composer (*dev*) 

```
php composer.phar require --dev teaandcode/behat-guzzle-extension
```

## Configuration

The extension is designed to require very little configuration, the only two
fields it requires is a `base_url` and a `service_descriptions` file location.

The `base_url` is the root url containing either a host name or IP address of
the API you're writing tests for, just make sure it's a fully qualified URL
(the trailing slash is not required) e.g. http://127.0.0.1

The `service_descriptions` file location is required as the extension is
designed to make use of a Guzzle service descriptions file, this means that all
the endpoints and associated fields you want to test should be listed as JSON in
the file for the extension to work.

Follow the link provided here if you want to know more about how to use the
[Guzzle service descriptions](http://guzzle3.readthedocs.org/webservice-client/guzzle-service-descriptions.html)
file or take a look at the example Guzzle service descriptions file excerpt
below.

### Example configuration in behat.yml

```yaml
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

### Example Guzzle service descriptions file

```json
{
    "name": "Travis API",
    "operations": {
        "GetReposBuilds": {
            "httpMethod": "GET",
            "uri": "repos/{slug}/builds",
            "summary": "Gets the last build for repo",
            "parameters": {
                "slug": {
                    "location": "uri",
                    "description": "Repo slug from GitHub",
                    "required": true
                },
                "number": {
                    "location": "query"
                }
            }
        }
    }
}
```

## Further configuration

As you might have seen in the example configuration above, it is possible to
pass a list of usernames or e-mail addresses that can be associated with an HTTP
header Authorization Bearer token, which means that you're able to test secure
parts of your API. Just specify the GuzzleContext as shown above in the
behat.yml file and provide and array of users utilising the username/e-mail
address ad the key with the bearer token as the value.

## Predefined steps

TO DO: In the meantime checkout the repo.feature file in the features directory
and the docblocks above each of the methods in the GuzzleContext.php file in the
src/Behat/GuzzleExtension/Context directory.

## To do

* Add documentation
* Add language support

## Copyright

Copyright (c) 2015 Dave Nash (knasher). See LICENSE for details.

## Contributors

* Dave Nash [knasher](http://github.com/knasher) [lead developer]
* Other [awesome developers]
  (https://github.com/teaandcode/behat-guzzle-extension/graphs/contributors)
