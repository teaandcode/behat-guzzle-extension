Feature: Repo
  As a user
  I would like to see build details for a repo

  Scenario: Getting the first build
    When I call "GetReposBuilds" with the following fields:
      | slug   | teaandcode/behat-guzzle-extension |
      | number | 1                                 |
    Then I get a successful response
    And the response contains 1 resource with the following data:
      | id       | repository_id | number | state    |
      | 51057115 | 3977770       | 1      | finished |
