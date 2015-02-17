Feature: Repo
  As a user
  I would like to see build details for a repo

  Scenario: Getting the last build details
    When I call "GetRepoBuild" with the following field:
      | slug | teaandcode/behat-guzzle-extension |
    Then I get a successful response
    And the response contains the following values:
      | repository_id | 3977770 |
