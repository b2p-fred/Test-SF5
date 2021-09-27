# features/Anonymous.feature
Feature: Anonymous
  As an anonymous user
  I am allowed to request some public endpoints
  I am denied to request some secured endpoints

  Background:
    Given the "Accept" request header is "application/ld+json"
    Given the "X-User-Agent" request header is "appName/appVersion (comments)"

  Scenario: Call a not found route
    When I request "not-found-route"
    Then the response code is 404

  Scenario: Call the API docs route
    When I request "docs"
    Then the response code is 200
#    And print the corresponding curl command
#    And print last JSON response

  Scenario: Call the API version route
    When I request "version"
    Then the response code is 200
#    And print the corresponding curl command
#    And print last JSON response

  Scenario: Call the API version route with some parameters
    Given the following query parameters are set:
      | key      | value  |
      | language | french |
      | client   | test2  |
    When I request "version"
    Then the response code is 200
    And print the corresponding curl command
    And print last JSON response

  Scenario Outline: As an anonymous user I can view non-secured pages
    Given I request "<uri>"
    Then the response code is 200
    Examples:
      | uri     |
      | version |
      | docs    |

  Scenario Outline: As an anonymous user I can view non-secured pages - 2
    Given I am not authenticated
    Given I request "<uri>"
    Then the response code is 200
    Examples:
      | uri     |
      | version |
      | docs    |

  Scenario Outline: As an anonymous user I cannot view secured pages
    Given I request "<uri>"
    Then the response code is 401
    Examples:
      | uri       |
      | addresses |
      | contacts  |
      | documents |
      | sites     |
      | users     |
