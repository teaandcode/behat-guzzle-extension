Feature: Repo
  As a user
  I would like to see the config and build details for a repo

  Scenario: Getting the config
    When I call "GetConfig"
    Then I get a successful response
    And the response contains the following values from JSON:
      """
        {
          "config": {
            "host": "travis-ci.org",
            "github": {
              "scopes": [
                "read:org"
              ]
            }
          }
        }
      """

  Scenario: Getting the first build
    When I call "GetReposBuilds" with the following values:
      | profile | teaandcode             |
      | repo    | behat-guzzle-extension |
      | number  | 1                      |
    Then I get a successful response
    And the response contains 1 resource with the following data:
      | id       | repository_id | number | state    |
      | 51057115 | 3977770       | 1      | finished |
